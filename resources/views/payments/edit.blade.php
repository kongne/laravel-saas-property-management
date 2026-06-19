@extends('layouts.app')
@section('title', 'Edit Payment')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Payment</h2>
    <a href="{{ route('payments.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Lease</label>
                    <select name="lease_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($leases as $l)
                            <option value="{{ $l->id }}" {{ $payment->lease_id == $l->id ? 'selected' : '' }}>{{ $l->tenant->user->name }} - {{ $l->unit->unit_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Amount</label><input type="number" name="amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->amount }}" step="0.01"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Paid Amount</label><input type="number" name="paid_amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->paid_amount }}" step="0.01"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Late Fee</label><input type="number" name="late_fee" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->late_fee }}" step="0.01"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Due Date</label><input type="date" name="due_date" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->due_date->format('Y-m-d') }}"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Paid Date</label><input type="date" name="paid_date" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->paid_date ? $payment->paid_date->format('Y-m-d') : '' }}"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" id="paymentMethodEdit">
                        <option value="">Select</option>
                        <option value="cash" {{ $payment->payment_method=='cash'?'selected':'' }}>Cash</option>
                        <option value="check" {{ $payment->payment_method=='check'?'selected':'' }}>Check</option>
                        <option value="bank_transfer" {{ $payment->payment_method=='bank_transfer'?'selected':'' }}>Bank Transfer</option>
                        <option value="credit_card" {{ $payment->payment_method=='credit_card'?'selected':'' }}>Credit Card</option>
                        <option value="mobile_money" {{ $payment->payment_method=='mobile_money'?'selected':'' }}>Mobile Money</option>
                        <option value="orange_money" {{ $payment->payment_method=='orange_money'?'selected':'' }}>Orange Money</option>
                        <option value="mtn_money" {{ $payment->payment_method=='mtn_money'?'selected':'' }}>MTN Money</option>
                        <option value="other" {{ $payment->payment_method=='other'?'selected':'' }}>Other</option>
                    </select>
                </div>
                <div id="mobileMoneyFieldEdit" style="{{ in_array($payment->payment_method,['orange_money','mtn_money']) ? '' : 'display:none' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Mobile Money Number</label>
                    <input type="text" name="mobile_money_number" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->mobile_money_number ?? old('mobile_money_number') }}" placeholder="e.g. 6XX XXX XXX">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Transaction Ref</label>
                    <input type="text" name="transaction_reference" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $payment->transaction_reference ?? old('transaction_reference') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['pending','paid','overdue','partial','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $payment->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-4"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Update Payment</button></div>
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('paymentMethodEdit').addEventListener('change',function(){document.getElementById('mobileMoneyFieldEdit').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
@endsection
