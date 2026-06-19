@extends('layouts.app')
@section('title', 'Record Payment')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Record Payment</h2>
    <a href="{{ route('payments.index') }}" class="btn-secondary btn-sm">Back</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Payment Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <x-forms.select name="lease_id" label="Lease" required :options="$leases->mapWithKeys(fn($l) => [$l->id => $l->tenant->user->name.' - '.$l->unit->unit_number.' ($'.number_format($l->rent_amount,2).')'])->toArray()" placeholder="Select Lease" />
                </div>
                <div><x-forms.input name="tenant_id" label="Tenant ID" type="number" placeholder="Enter tenant ID" /></div>
                <div><x-forms.input name="unit_id" label="Unit ID" type="number" placeholder="Enter unit ID" /></div>
                <div><x-forms.input name="amount" label="Amount" type="number" step="0.01" required /></div>
                <div><x-forms.input name="paid_amount" label="Paid Amount" type="number" step="0.01" /></div>
                <div><x-forms.input name="late_fee" label="Late Fee" type="number" step="0.01" :value="old('late_fee', 0)" /></div>
                <div><x-forms.input name="due_date" label="Due Date" type="date" required /></div>
                <div><x-forms.input name="paid_date" label="Paid Date" type="date" /></div>
                <div>
                    <x-forms.select name="payment_method" label="Payment Method" id="paymentMethod" :options="['' => 'Select', 'cash' => 'Cash', 'check' => 'Check', 'bank_transfer' => 'Bank Transfer', 'credit_card' => 'Credit Card', 'mobile_money' => 'Mobile Money', 'orange_money' => 'Orange Money', 'mtn_money' => 'MTN Money', 'other' => 'Other']" />
                </div>
                <div id="mobileMoneyField" style="display:none"><x-forms.input name="mobile_money_number" label="Mobile Money Number" placeholder="e.g. 6XX XXX XXX" /></div>
                <div><x-forms.input name="transaction_reference" label="Transaction Ref" /></div>
                <div class="lg:col-span-4"><x-forms.textarea name="notes" label="Notes" rows="2" /></div>
                <div class="lg:col-span-4"><x-forms.button variant="primary">Record Payment</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('paymentMethod')?.addEventListener('change',function(){document.getElementById('mobileMoneyField').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
@endsection