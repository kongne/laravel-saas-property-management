@extends('layouts.app')
@section('title', __('Payment').' '.$payment->invoice_number)
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Payments'), 'url' => route('payments.index')],
        ['label' => __('Payment').' '.$payment->invoice_number],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Payment') }} {{ $payment->invoice_number }}</h2>
    <div class="flex gap-2">
        <a href="{{ route('payments.receipt', $payment) }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300 inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('Receipt') }}
        </a>
        <a href="{{ route('payments.edit', $payment) }}" class="bg-amber-400 text-white px-4 py-2 rounded-lg hover:bg-amber-500 transition-colors font-medium text-sm inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            {{ __('Edit') }}
        </a>
        <a href="{{ route('payments.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">{{ __('Back') }}</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">{{ __('Payment Details') }}</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>{{ __('Invoice') }}:</strong> {{ $payment->invoice_number }}</div>
                    <div><strong>{{ __('Status') }}:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($payment->status) }}</span></div>
                    <div><strong>{{ __('Tenant') }}:</strong> {{ $payment->tenant->user->name ?? 'N/A' }}</div>
                    <div><strong>{{ __('Unit') }}:</strong> {{ $payment->unit->unit_number ?? 'N/A' }}</div>
                    <div><strong>{{ __('Amount') }}:</strong> ${{ number_format($payment->amount, 2) }}</div>
                    <div><strong>{{ __('Paid') }}:</strong> ${{ number_format($payment->paid_amount ?? 0, 2) }}</div>
                    <div><strong>{{ __('Balance') }}:</strong> ${{ number_format($payment->balance, 2) }}</div>
                    <div><strong>{{ __('Late Fee') }}:</strong> ${{ number_format($payment->late_fee, 2) }}</div>
                    <div><strong>{{ __('Due Date') }}:</strong> {{ $payment->due_date->format('M d, Y') }}</div>
                    <div><strong>{{ __('Paid Date') }}:</strong> {{ $payment->paid_date ? $payment->paid_date->format('M d, Y') : 'N/A' }}</div>
                    <div><strong>{{ __('Method') }}:</strong> {{ $payment->payment_method ? ucfirst(str_replace('_',' ',$payment->payment_method)) : 'N/A' }}</div>
                    <div><strong>{{ __('Mobile Money #') }}:</strong> {{ $payment->mobile_money_number ?? 'N/A' }}</div>
                    <div><strong>{{ __('Reference') }}:</strong> {{ $payment->transaction_reference ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        @if($payment->status !== 'paid')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">{{ __('Mark as Paid') }}</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('payments.mark-as-paid', $payment) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Amount Paid') }}</label>
                        <input type="number" name="paid_amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->amount }}" step="0.01" required>
                    </div>
                    <div>
                        <select name="payment_method" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" id="payMethod">
                            <option value="">{{ __('Method') }}</option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="check">{{ __('Check') }}</option>
                            <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                            <option value="credit_card">{{ __('Credit Card') }}</option>
                            <option value="mobile_money">{{ __('Mobile Money') }}</option>
                            <option value="orange_money">{{ __('Orange Money') }}</option>
                            <option value="mtn_money">{{ __('MTN Money') }}</option>
                        </select>
                    </div>
                    <div id="mobilePayField" style="display:none"><input type="text" name="mobile_money_number" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Mobile money number"></div>
                    <div><input type="text" name="transaction_reference" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Reference"></div>
                    <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-medium text-sm w-full">{{ __('Mark as Paid') }}</button>
                    <script>document.getElementById('payMethod').addEventListener('change',function(){document.getElementById('mobilePayField').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
