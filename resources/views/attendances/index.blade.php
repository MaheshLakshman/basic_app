@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Attendances</h6>
    <button type="button" class="btn btn-primary" id="create-attendance-btn">Record Attendance</button>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="filter-form" class="row align-items-end g-3">
            <div class="col-md-3">
                <label for="filter-date" class="form-label small fw-bold">Date</label>
                <input type="date" class="form-control" id="filter-date">
            </div>
            <div class="col-md-3">
                <label for="filter-user" class="form-label small fw-bold">User</label>
                <select class="form-select" id="filter-user">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter-status" class="form-label small fw-bold">Status</label>
                <select class="form-select" id="filter-status">
                    <option value="">All Status</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="half_day">Half Day</option>
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
            <table class="table table-hover w-100" id="attendances-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th width="150px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Attendance Modal -->
<div class="modal fade" id="attendance-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Attendance Form</h5>
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
    let table = $('#attendances-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('attendances.index') }}",
            data: function (d) {
                d.date = $('#filter-date').val();
                d.user_id = $('#filter-user').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'attendance_day', name: 'attendance_day' },
            { data: 'user_name', name: 'user_name' },
            { data: 'attendance_type', name: 'attendance_type' },
            { data: 'branch_name', name: 'branch_name' },
            { 
                 data: 'check_in_at', 
                 name: 'check_in_at',
                 render: function(data) {
                    return data ? new Date(data).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '-';
                 }
            },
            { 
                 data: 'check_out_at', 
                 name: 'check_out_at',
                 render: function(data) {
                    return data ? new Date(data).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '-';
                 }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    let badgeClass = 'bg-secondary';
                    if(data === 'present') badgeClass = 'bg-success';
                    else if(data === 'absent') badgeClass = 'bg-danger';
                    else if(data === 'half_day') badgeClass = 'bg-warning text-dark';
                    return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
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

    const modal = new bootstrap.Modal(document.getElementById('attendance-modal'));
    const modalBody = $('#modal-body-content');

    $('#create-attendance-btn').on('click', function() {
        $('#attendanceModalLabel').text('Record Attendance');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();
        $.get("attendances/create", function(data) { modalBody.html(data); });
    });

    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $('#attendanceModalLabel').text('Edit Attendance');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        modal.show();
        $.get(`attendances/${id}/edit`, function(data) { modalBody.html(data); });
    });

    $(document).on('submit', '#attendance-form', function(e) {
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
                        $(`[name="${key}"]`).addClass('is-invalid').after(`<div class="invalid-feedback">${value[0]}</div>`);
                    });
                } else {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            }
        });
    });

    // Delete Logic (Standard)
    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Delete this record?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `attendances/${id}`,
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
