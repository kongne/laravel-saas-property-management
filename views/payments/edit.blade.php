@extends('layouts.app')
@section('title', 'Edit Payment')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Payment</h2>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <select name="lease_id" class="form-select">
                        @foreach($leases as $l)
                            <option value="{{ $l->id }}" {{ $payment->lease_id == $l->id ? 'selected' : '' }}>{{ $l->tenant->user->name }} - {{ $l->unit->unit_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" step="0.01"></div>
                <div class="col-md-3"><input type="number" name="paid_amount" class="form-control" value="{{ $payment->paid_amount }}" step="0.01"></div>
                <div class="col-md-3"><input type="number" name="late_fee" class="form-control" value="{{ $payment->late_fee }}" step="0.01"></div>
                <div class="col-md-3"><input type="date" name="due_date" class="form-control" value="{{ $payment->due_date->format('Y-m-d') }}"></div>
                <div class="col-md-3"><input type="date" name="paid_date" class="form-control" value="{{ $payment->paid_date ? $payment->paid_date->format('Y-m-d') : '' }}"></div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" id="paymentMethodEdit">
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
                <div class="col-md-3" id="mobileMoneyFieldEdit" style="{{ in_array($payment->payment_method,['orange_money','mtn_money']) ? '' : 'display:none' }}">
                    <label class="form-label">Mobile Money Number</label>
                    <input type="text" name="mobile_money_number" class="form-control" value="{{ $payment->mobile_money_number ?? old('mobile_money_number') }}" placeholder="e.g. 6XX XXX XXX">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transaction Ref</label>
                    <input type="text" name="transaction_reference" class="form-control" value="{{ $payment->transaction_reference ?? old('transaction_reference') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pending','paid','overdue','partial','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $payment->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Update Payment</button></div>
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('paymentMethodEdit').addEventListener('change',function(){document.getElementById('mobileMoneyFieldEdit').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
@endsection
