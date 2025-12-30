@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Price Adjustments</h6>
    <button type="button" class="btn btn-primary" id="create-btn">Create Adjustment</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-100" id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Applied Month</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
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
        ajax: "{{ route('admin.price_adjustments.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'member_name', name: 'member.name' },
            { data: 'type', name: 'type' },
            { data: 'amount', name: 'amount' },
            { data: 'applied_month', name: 'applied_month' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    const modal = new bootstrap.Modal(document.getElementById('modal'));
    const modalBody = $('#modal-body-content');

    $('#create-btn').on('click', function() {
        $('#modalLabel').text('Create Price Adjustment');
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modal.show();
        $.get("{{ route('admin.price_adjustments.create') }}", function(data) {
            modalBody.html(data);
        });
    });
});
</script>
@endpush
