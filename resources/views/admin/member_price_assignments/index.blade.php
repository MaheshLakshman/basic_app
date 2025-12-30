@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Member Price Assignments</h6>
    <button type="button" class="btn btn-primary" id="create-btn">Assign Price Plan</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-100" id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member Name</th>
                        <th>Plan Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th width="150px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Form loaded via AJAX -->
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
    let table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.member_price_assignments.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'member_name', name: 'member.name' }, // Ensure correct column name for searching if using joins or addColumn
            { data: 'plan_name', name: 'pricePlan.name' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { 
                 data: 'is_active', 
                 name: 'is_active',
                 render: function(data) {
                     return data ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                 }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    const modal = new bootstrap.Modal(document.getElementById('modal'));
    const modalBody = $('#modal-body-content');

    $('#create-btn').on('click', function() {
        $('#modalLabel').text('Assign Plan');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modal.show();
        $.get("{{ route('admin.member_price_assignments.create') }}", function(data) {
            modalBody.html(data);
        });
    });

    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        $('#modalLabel').text('Edit Assignment');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modal.show();
        $.get(url, function(data) {
            modalBody.html(data);
        });
    });
});
</script>
@endpush
