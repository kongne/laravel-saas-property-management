@extends('layouts.app')
@section('title', 'Receipt')
@push('styles')
<style>
@media print {
    body, html { margin: 0; padding: 0; }
    nav, #sidebar, .sidebar, .navbar, .header, .footer,
    .card-footer, .btn, .no-print { display: none !important; }
    .print-card { border: none !important; box-shadow: none !important; }
    .print-body { padding: 0 !important; }
    .max-w-100 { max-width: 100%; width: 100%; }
    .container { max-width: 100%; padding: 20px; }
    @page { margin: 0.5in; }
}
</style>
@endpush
@section('content')
<div class="flex justify-center no-print">
    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 print-card" id="receipt">
            <div class="p-8 print-body">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800">{{ config('app.name') }}</h3>
                    <h5 class="text-lg font-semibold text-slate-600 mt-1">Payment Receipt</h5>
                    <p class="text-slate-500 text-sm">{{ $payment->invoice_number }}</p>
                </div>
                <hr class="border-slate-200 mb-6">
                <div class="flex justify-between mb-4">
                    <div><strong>Tenant:</strong> {{ $payment->tenant->user->name ?? 'N/A' }}</div>
                    <div class="text-right"><strong>Date:</strong> {{ ($payment->paid_date ?? $payment->created_at)->format('M d, Y') }}</div>
                </div>
                <div class="mb-4"><strong>Unit:</strong> {{ $payment->unit->unit_number ?? 'N/A' }} ({{ $payment->unit->property->name ?? 'N/A' }})</div>
                <table class="w-full text-sm mb-6">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Description</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3 border-t border-slate-100 text-slate-600">Rent Payment</td>
                            <td class="px-4 py-3 border-t border-slate-100 text-slate-600 text-right">${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @if($payment->late_fee > 0)
                        <tr>
                            <td class="px-4 py-3 border-t border-slate-100 text-slate-600">Late Fee</td>
                            <td class="px-4 py-3 border-t border-slate-100 text-slate-600 text-right">${{ number_format($payment->late_fee, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="px-4 py-3 border-t border-slate-200 text-slate-800 text-left">Total</th>
                            <th class="px-4 py-3 border-t border-slate-200 text-slate-800 text-right">${{ number_format($payment->amount + $payment->late_fee, 2) }}</th>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 border-t border-slate-200 text-slate-800 text-left">Paid</th>
                            <th class="px-4 py-3 border-t border-slate-200 text-slate-800 text-right">${{ number_format($payment->paid_amount ?? 0, 2) }}</th>
                        </tr>
                        @if($payment->balance > 0)
                        <tr>
                            <th class="px-4 py-3 border-t border-slate-200 text-red-700 text-left">Balance</th>
                            <th class="px-4 py-3 border-t border-slate-200 text-red-700 text-right">${{ number_format($payment->balance, 2) }}</th>
                        </tr>
                        @endif
                    </tfoot>
                </table>
                <hr class="border-slate-200 mb-4">
                <div class="flex justify-between text-slate-500 text-sm">
                    <div><strong>Method:</strong> {{ $payment->payment_method ? ucfirst(str_replace('_',' ',$payment->payment_method)) : 'N/A' }}</div>
                    <div class="text-center"><strong>Mobile:</strong> {{ $payment->mobile_money_number ?? 'N/A' }}</div>
                    <div class="text-right"><strong>Ref:</strong> {{ $payment->transaction_reference ?? 'N/A' }}</div>
                </div>
                <div class="text-center mt-6">
                    <p class="text-slate-500">Thank you for your payment!</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 text-center no-print">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center gap-1.5" onclick="window.print()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
