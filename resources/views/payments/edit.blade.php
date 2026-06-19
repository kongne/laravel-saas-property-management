@extends('layouts.app')
@section('title', 'Edit Payment')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Payment</h2>
    <a href="{{ route('payments.index') }}" class="btn-secondary btn-sm">Back</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Payment Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <x-forms.select name="lease_id" label="Lease" :options="$leases->mapWithKeys(fn($l) => [$l->id => $l->tenant->user->name.' - '.$l->unit->unit_number])->toArray()" :value="old('lease_id', $payment->lease_id)" />
                </div>
                <div><x-forms.input name="amount" label="Amount" type="number" step="0.01" :value="old('amount', $payment->amount)" /></div>
                <div><x-forms.input name="paid_amount" label="Paid Amount" type="number" step="0.01" :value="old('paid_amount', $payment->paid_amount)" /></div>
                <div><x-forms.input name="late_fee" label="Late Fee" type="number" step="0.01" :value="old('late_fee', $payment->late_fee)" /></div>
                <div><x-forms.input name="due_date" label="Due Date" type="date" :value="old('due_date', $payment->due_date->format('Y-m-d'))" /></div>
                <div><x-forms.input name="paid_date" label="Paid Date" type="date" :value="old('paid_date', $payment->paid_date ? $payment->paid_date->format('Y-m-d') : '')" /></div>
                <div>
                    <x-forms.select name="payment_method" label="Payment Method" id="paymentMethodEdit" :options="['' => 'Select', 'cash' => 'Cash', 'check' => 'Check', 'bank_transfer' => 'Bank Transfer', 'credit_card' => 'Credit Card', 'mobile_money' => 'Mobile Money', 'orange_money' => 'Orange Money', 'mtn_money' => 'MTN Money', 'other' => 'Other']" :value="old('payment_method', $payment->payment_method)" />
                </div>
                <div id="mobileMoneyFieldEdit" style="{{ in_array($payment->payment_method ?? '', ['orange_money', 'mtn_money']) ? '' : 'display:none' }}">
                    <x-forms.input name="mobile_money_number" label="Mobile Money Number" :value="old('mobile_money_number', $payment->mobile_money_number ?? '')" placeholder="e.g. 6XX XXX XXX" />
                </div>
                <div><x-forms.input name="transaction_reference" label="Transaction Ref" :value="old('transaction_reference', $payment->transaction_reference ?? '')" /></div>
                <div>
                    <x-forms.select name="status" label="Status" :options="['pending' => 'Pending', 'paid' => 'Paid', 'overdue' => 'Overdue', 'partial' => 'Partial', 'cancelled' => 'Cancelled']" :value="old('status', $payment->status)" />
                </div>
                <div class="lg:col-span-4"><x-forms.button variant="primary">Update Payment</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('paymentMethodEdit')?.addEventListener('change',function(){document.getElementById('mobileMoneyFieldEdit').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
@endsection