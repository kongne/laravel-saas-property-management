@extends('layouts.app')
@section('title', 'Record Payment')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Record Payment</h2>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Lease *</label>
                    <select name="lease_id" class="form-select" required>
                        <option value="">Select Lease</option>
                        @foreach($leases as $l)
                            <option value="{{ $l->id }}">{{ $l->tenant->user->name }} - {{ $l->unit->unit_number }} (${{ number_format($l->rent_amount,2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Tenant ID</label><input type="number" name="tenant_id" class="form-control" value="{{ old('tenant_id') }}" placeholder="Enter tenant ID"></div>
                <div class="col-md-3"><label class="form-label">Unit ID</label><input type="number" name="unit_id" class="form-control" value="{{ old('unit_id') }}" placeholder="Enter unit ID"></div>
                <div class="col-md-3"><label class="form-label">Amount *</label><input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.01" required></div>
                <div class="col-md-3"><label class="form-label">Paid Amount</label><input type="number" name="paid_amount" class="form-control" value="{{ old('paid_amount') }}" step="0.01"></div>
                <div class="col-md-3"><label class="form-label">Late Fee</label><input type="number" name="late_fee" class="form-control" value="{{ old('late_fee', 0) }}" step="0.01"></div>
                <div class="col-md-3"><label class="form-label">Due Date *</label><input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required></div>
                <div class="col-md-3"><label class="form-label">Paid Date</label><input type="date" name="paid_date" class="form-control" value="{{ old('paid_date') }}"></div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" id="paymentMethod">
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
                <div class="col-md-3" id="mobileMoneyField" style="display:none"><label class="form-label">Mobile Money Number</label><input type="text" name="mobile_money_number" class="form-control" value="{{ old('mobile_money_number') }}" placeholder="e.g. 6XX XXX XXX"></div>
                <div class="col-md-3"><label class="form-label">Transaction Ref</label><input type="text" name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}"></div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Record Payment</button></div>
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('paymentMethod').addEventListener('change',function(){document.getElementById('mobileMoneyField').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
@endsection
