@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Languages</h6>
    <button type="button" class="btn btn-primary" id="create-lang-btn">Create Language</button>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="filter-form" class="row align-items-end g-3">
            <div class="col-md-4">
                <label for="filter-name" class="form-label small fw-bold">Language Name</label>
                <input type="text" class="form-control" id="filter-name" placeholder="Search by name...">
            </div>
            <div class="col-md-3">
                <label for="filter-code" class="form-label small fw-bold">Language Code</label>
                <input type="text" class="form-control" id="filter-code" placeholder="Search by code...">
            </div>
            <div class="col-md-5 d-flex gap-2">
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
            <table class="table table-hover w-100" id="languages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th width="150px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Language Modal -->
<div class="modal fade" id="language-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="languageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="languageModalLabel">Language Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
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
    let table = $('#languages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('languages.index') }}",
            data: function (d) {
                d.name = $('#filter-name').val();
                d.code = $('#filter-code').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'is_active', name: 'is_active' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    const modal = new bootstrap.Modal(document.getElementById('language-modal'));
    const modalBody = $('#modal-body-content');

    $('#search-btn').on('click', function() {
        table.draw();
    });

    $('#clear-btn').on('click', function() {
        $('#filter-form')[0].reset();
        table.draw();
    });

    $('#create-lang-btn').on('click', function() {
        $('#languageModalLabel').text('Create Language');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();

        $.get("languages/create", function(data) {
            modalBody.html(data);
        });
    });

    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $('#languageModalLabel').text('Edit Language');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();

        $.get(`languages/${id}/edit`, function(data) {
            modalBody.html(data);
        });
    });

    $(document).on('submit', '#language-form', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();
        let saveBtn = $('#save-btn');

        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                modal.hide();
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                saveBtn.prop('disabled', false).text('Save Changes');
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $.each(errors, function(key, value) {
                        let field = $(`#${key}`);
                        field.addClass('is-invalid');
                        field.after(`<div class="invalid-feedback">${value[0]}</div>`);
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
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `languages/${id}`,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire('Deleted!', response.success, 'success');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Could not delete the record.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
