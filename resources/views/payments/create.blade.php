@extends('layouts.app')
@section('title', 'Record Payment')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Record Payment</h2>
    <a href="{{ route('payments.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Lease *</label>
                    <select name="lease_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        <option value="">Select Lease</option>
                        @foreach($leases as $l)
                            <option value="{{ $l->id }}">{{ $l->tenant->user->name }} - {{ $l->unit->unit_number }} (${{ number_format($l->rent_amount,2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Tenant ID</label><input type="number" name="tenant_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('tenant_id') }}" placeholder="Enter tenant ID"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Unit ID</label><input type="number" name="unit_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('unit_id') }}" placeholder="Enter unit ID"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Amount *</label><input type="number" name="amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('amount') }}" step="0.01" required></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Paid Amount</label><input type="number" name="paid_amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('paid_amount') }}" step="0.01"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Late Fee</label><input type="number" name="late_fee" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('late_fee', 0) }}" step="0.01"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Due Date *</label><input type="date" name="due_date" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('due_date') }}" required></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Paid Date</label><input type="date" name="paid_date" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('paid_date') }}"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" id="paymentMethod">
                        <option value="">Select</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="orange_money">Orange Money</option>
                        <option value="mtn_money">MTN Money</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div id="mobileMoneyField" style="display:none"><label class="block text-sm font-medium text-slate-700 mb-1.5">Mobile Money Number</label><input type="text" name="mobile_money_number" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('mobile_money_number') }}" placeholder="e.g. 6XX XXX XXX"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Transaction Ref</label><input type="text" name="transaction_reference" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('transaction_reference') }}"></div>
                <div class="lg:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label><textarea name="notes" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="2">{{ old('notes') }}</textarea></div>
                <div class="lg:col-span-4"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Record Payment</button></div>
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('paymentMethod').addEventListener('change',function(){document.getElementById('mobileMoneyField').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
@endsection
