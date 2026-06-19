@extends('layouts.app')
@section('title', 'Receipt')
@push('styles')
<style>
@media print {
    body, html { margin: 0; padding: 0; }
    nav, #sidebar, .sidebar, .navbar, .header, .footer,
    .card-footer, .btn, .no-print, [class*="sidebar"], [class*="navbar"] { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
    .card-body { padding: 0 !important; }
    .col-md-6 { max-width: 100%; width: 100%; flex: 0 0 100%; }
    .container { max-width: 100%; padding: 20px; }
    .row { margin: 0; }
    @page { margin: 0.5in; }
}
</style>
@endpush
@section('content')
<div class="row justify-content-center no-print">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-5" id="receipt">
                <div class="text-center mb-4">
                    <h3>{{ config('app.name') }}</h3>
                    <h5>Payment Receipt</h5>
                    <p class="text-muted">{{ $payment->invoice_number }}</p>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6"><strong>Tenant:</strong> {{ $payment->tenant->user->name ?? 'N/A' }}</div>
                    <div class="col-6 text-end"><strong>Date:</strong> {{ ($payment->paid_date ?? $payment->created_at)->format('M d, Y') }}</div>
                </div>
                <div class="mb-3"><strong>Unit:</strong> {{ $payment->unit->unit_number ?? 'N/A' }} ({{ $payment->unit->property->name ?? 'N/A' }})</div>
                <table class="table">
                    <thead><tr><th>Description</th><th class="text-end">Amount</th></tr></thead>
                    <tbody>
                        <tr><td>Rent Payment</td><td class="text-end">${{ number_format($payment->amount, 2) }}</td></tr>
                        @if($payment->late_fee > 0)
                        <tr><td>Late Fee</td><td class="text-end">${{ number_format($payment->late_fee, 2) }}</td></tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr><th>Total</th><th class="text-end">${{ number_format($payment->amount + $payment->late_fee, 2) }}</th></tr>
                        <tr><th>Paid</th><th class="text-end">${{ number_format($payment->paid_amount ?? 0, 2) }}</th></tr>
                        @if($payment->balance > 0)
                        <tr><th class="text-danger">Balance</th><th class="text-end text-danger">${{ number_format($payment->balance, 2) }}</th></tr>
                        @endif
                    </tfoot>
                </table>
                <hr>
                <div class="row text-muted small">
                    <div class="col-4"><strong>Method:</strong> {{ $payment->payment_method ? ucfirst(str_replace('_',' ',$payment->payment_method)) : 'N/A' }}</div>
                    <div class="col-4 text-center"><strong>Mobile:</strong> {{ $payment->mobile_money_number ?? 'N/A' }}</div>
                    <div class="col-4 text-end"><strong>Ref:</strong> {{ $payment->transaction_reference ?? 'N/A' }}</div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Thank you for your payment!</p>
                </div>
            </div>
            <div class="card-footer text-center no-print">
                <button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print Receipt</button>
            </div>
        </div>
    </div>
</div>
@endsection
