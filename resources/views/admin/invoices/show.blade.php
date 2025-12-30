@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
        <button onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i> Print</button>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-5">
            <div class="row mb-5">
                <div class="col-md-6">
                    <h4 class="fw-bold text-primary">INVOICE</h4>
                    <p class="text-muted text-uppercase small">Invoice No: {{ $invoice->invoice_no }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="fw-bold">{{ $invoice->organization->name }}</h5>
                    <p class="small text-muted mb-0">{{ $invoice->branch->name ?? 'Main Branch' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted small fw-bold">Bill To</h6>
                    <h5 class="mb-0">{{ $invoice->user->name }}</h5>
                    <p class="mb-0">{{ $invoice->user->email }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6 class="text-uppercase text-muted small fw-bold">Date</h6>
                    <p class="mb-0">{{ $invoice->created_at->format('M d, Y') }}</p>
                    <h6 class="text-uppercase text-muted small fw-bold mt-2">Period</h6>
                    <p class="mb-0">{{ $invoice->period_start->format('M d, Y') }} - {{ $invoice->period_end->format('M d, Y') }}</p>
                </div>
            </div>

            <table class="table table-bordered mb-4">
                <thead class="bg-light">
                    <tr>
                        <th>Description</th>
                        <th class="text-center" width="100">Qty</th>
                        <th class="text-end" width="150">Unit Price</th>
                        <th class="text-end" width="150">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ json_decode($item->description)->en ?? $item->description }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-6">
                    @if($invoice->adjustments->count() > 0)
                        <h6 class="text-uppercase text-muted small fw-bold">Adjustments</h6>
                        <ul class="list-unstyled">
                            @foreach($invoice->adjustments as $adj)
                                <li class="small d-flex justify-content-between text-muted">
                                    <span>{{ $adj->reason }}</span>
                                    <span>
                                        {{ $adj->type == 'credit' ? '-' : '+' }}{{ number_format($adj->amount, 2) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-md-4 ms-auto">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end">{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount_total > 0)
                        <tr>
                            <td class="text-success">Discount:</td>
                            <td class="text-end text-success">-{{ number_format($invoice->discount_total, 2) }}</td>
                        </tr>
                        @endif
                        @if($invoice->adjustment_total > 0)
                        <tr>
                            <td>Adjustments:</td>
                            <td class="text-end">+{{ number_format($invoice->adjustment_total, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="fw-bold fs-5 border-top">
                            <td>Total:</td>
                            <td class="text-end">{{ number_format($invoice->grand_total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-5 text-center text-muted small">
                <p>Thank you for your business!</p>
            </div>
        </div>
    </div>
</div>
@endsection
