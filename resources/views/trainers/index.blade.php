@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Trainers</h6>
    <button type="button" class="btn btn-primary" id="create-trainer-btn">Add Trainer</button>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="filter-form" class="row align-items-end g-3">
             <div class="col-md-3">
                <label for="filter-organization" class="form-label small fw-bold">Organization</label>
                <select class="form-select" id="filter-organization">
                    <option value="">All Organizations</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-name" class="form-label small fw-bold">Name</label>
                <input type="text" class="form-control" id="filter-name" placeholder="Search by name...">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="button" class="btn btn-primary px-4" id="search-btn">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                <button type="button" class="btn btn-outline-secondary px-4" id="clear-btn">
                    <i class="bi bi-x-circle me-1"></i> Clear
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-100" id="trainers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>Specialization</th>
                        <th>Experience</th>
                        <th width="150px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Trainer Modal -->
<div class="modal fade" id="trainer-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="trainerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="trainerModalLabel">Trainer Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Form will be loaded here via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    let table = $('#trainers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('trainers.index') }}",
            data: function (d) {
                d.organization_id = $('#filter-organization').val();
                d.name = $('#filter-name').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'organization_name', name: 'organization_name', orderable: false },
             { data: 'specialization', name: 'specialization', orderable: false },
            { data: 'experience', name: 'experience', orderable: false },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            }
        ]
    });

    $('#search-btn').on('click', function() { table.draw(); });
    $('#clear-btn').on('click', function() { $('#filter-form')[0].reset(); table.draw(); });

    const modal = new bootstrap.Modal(document.getElementById('trainer-modal'));
    const modalBody = $('#modal-body-content');

    $('#create-trainer-btn').on('click', function() {
        $('#trainerModalLabel').text('Add Trainer');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();
        $.get("trainers/create", function(data) { modalBody.html(data); });
    });

    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $('#trainerModalLabel').text('Edit Trainer');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();
        $.get(`trainers/${id}/edit`, function(data) { modalBody.html(data); });
    });

    $(document).on('submit', '#trainer-form', function(e) {
        e.preventDefault();
        let form = $(this);
        let saveBtn = $('#save-btn');
        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                modal.hide();
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Success', text: response.success, timer: 1500, showConfirmButton: false });
            },
            error: function(xhr) {
                saveBtn.prop('disabled', false).text('Save');
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    $.each(errors, function(key, value) {
                        let field = $(`[name="${key}"]`);
                        if(key.includes('.')) {
                             let parts = key.split('.');
                             field = $(`[name="${parts[0]}[${parts[1]}]"]`);
                        }
                        field.addClass('is-invalid');
                        if (field.next('.invalid-feedback').length === 0) {
                            field.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        } else {
                            field.next('.invalid-feedback').text(value[0]);
                        }
                    });
                } else {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Delete this trainer?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `trainers/${id}`,
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}", _method: 'DELETE' },
                    success: function(resp) {
                        table.ajax.reload();
                        Swal.fire('Deleted', resp.success, 'success');
                    }
                });
            }
        });
    });
});
</script>
@endpush
