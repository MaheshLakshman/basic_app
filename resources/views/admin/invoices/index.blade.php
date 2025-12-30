@extends('layouts.admin')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0">Invoices</h6>
    <button type="button" class="btn btn-primary" id="create-btn">Generate Invoice</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-100" id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice No</th>
                        <th>User</th>
                        <th>Period</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="150px">Actions</th>
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
                <h5 class="modal-title" id="modalLabel">Generate Invoice</h5>
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
        ajax: "{{ route('admin.invoices.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'user_name', name: 'user.name' },
            { 
                data: 'period_start', 
                name: 'period_start',
                render: function(data, type, row) {
                    return `${row.period_start} to ${row.period_end}`;
                }
            },
            { data: 'grand_total', name: 'grand_total' },
            { 
                 data: 'status', 
                 name: 'status',
                 render: function(data) {
                     let badgeClass = data === 'paid' ? 'bg-success' : 'bg-warning';
                     return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                 }
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    const modal = new bootstrap.Modal(document.getElementById('modal'));
    const modalBody = $('#modal-body-content');

    $('#create-btn').on('click', function() {
        modalBody.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modal.show();
        $.get("{{ route('admin.invoices.create') }}", function(data) {
            modalBody.html(data);
        });
    });
});
</script>
@endpush
