@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Price Plans</h6>
    <button type="button" class="btn btn-primary" id="create-btn">Create Price Plan</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-100" id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Billing Type</th>
                        <th>Duration (Days)</th>
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
        ajax: "{{ route('admin.price_plans.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'price', name: 'price' },
            { data: 'billing_type', name: 'billing_type' },
            { data: 'duration_days', name: 'duration_days' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    const modal = new bootstrap.Modal(document.getElementById('modal'));
    const modalBody = $('#modal-body-content');

    $('#create-btn').on('click', function() {
        $('#modalLabel').text('Create Price Plan');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modal.show();
        $.get("{{ route('admin.price_plans.create') }}", function(data) {
            modalBody.html(data);
        });
    });

    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        let url = $(this).attr('href'); // Use href from the button
        $('#modalLabel').text('Edit Price Plan');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modal.show();
        $.get(url, function(data) {
            modalBody.html(data);
        });
    });

    // Handle Form Submit inside Modal
   // Note: The form itself should handle submission via AJAX or standard POST. 
   // Given the organization example used mixed logic, I'll rely on standard form submission within the modal content or basic ajax if feasible without complex generic handlers.
   // For now, I won't add the generic AJAX form handler here unless required, assuming the 'create' and 'edit' views return a form that posts to the store/update routes. 
   // However, to keep it modal-friendly, AJAX submission is better.
});
</script>
@endpush
