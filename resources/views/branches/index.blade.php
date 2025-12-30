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
    <h6 class="mb-0">Branches</h6>
    <button type="button" class="btn btn-primary" id="create-branch-btn">Create Branch</button>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="filter-form" class="row align-items-end g-3">
            <div class="col-md-3">
                <label for="filter-name" class="form-label small fw-bold">Branch Name</label>
                <input type="text" class="form-control" id="filter-name" placeholder="Search by name...">
            </div>
            <div class="col-md-3">
                <label for="filter-organization" class="form-label small fw-bold">Organization</label>
                <select class="form-select" id="filter-organization">
                    <option value="">All Organizations</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter-status" class="form-label small fw-bold">Status</label>
                <select class="form-select" id="filter-status">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
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
            <table class="table table-hover w-100" id="branches-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>Parent Branch</th>
                        <th>Status</th>
                        <th width="150px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Branch Modal -->
<div class="modal fade" id="branch-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="branchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="branchModalLabel">Branch Form</h5>
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
    let table = $('#branches-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('branches.index') }}",
            data: function (d) {
                d.name = $('#filter-name').val();
                d.organization_id = $('#filter-organization').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'organization_name', name: 'organization_name' },
            { data: 'parent_name', name: 'parent_name' },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    let badgeClass = data === 'active' ? 'bg-success' : 'bg-secondary';
                    return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="${row.id}">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}">Delete</button>
                        </div>
                    `;
                }
            }
        ]
    });

    // Custom Filters Logic
    $('#search-btn').on('click', function() {
        table.draw();
    });

    $('#clear-btn').on('click', function() {
        $('#filter-form')[0].reset();
        table.draw();
    });

    const modal = new bootstrap.Modal(document.getElementById('branch-modal'));
    const modalBody = $('#modal-body-content');

    // Create Branch
    $('#create-branch-btn').on('click', function() {
        $('#branchModalLabel').text('Create Branch');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();

        $.get("branches/create", function(data) {
            modalBody.html(data);
        });
    });

    // Edit Branch
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $('#branchModalLabel').text('Edit Branch');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();

        $.get(`branches/${id}/edit`, function(data) {
            modalBody.html(data);
        });
    });

    // Submit Form (Handle both Create and Update)
    $(document).on('submit', '#branch-form', function(e) {
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
                        // Handle array fields (translations) logic
                        // e.g. name.en -> input[name="name[en]"]
                        let field;
                        if (key.includes('.')) {
                            let parts = key.split('.');
                            field = $(`[name="${parts[0]}[${parts[1]}]"]`);
                        } else {
                            field = $(`[name="${key}"]`);
                        }
                        
                        field.addClass('is-invalid');
                        // Place error message
                        if(field.length > 0) {
                            // Check if next is invalid feedback, if so replace, if not append
                            if(field.next('.invalid-feedback').length === 0) {
                                field.after(`<div class="invalid-feedback">${value[0]}</div>`);
                            } else {
                                field.next('.invalid-feedback').text(value[0]);
                            }
                        }
                    });
                } else {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            }
        });
    });

    // Delete Branch
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
                    url: `branches/${id}`,
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
