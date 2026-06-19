@extends('layouts.app')
@section('title', 'Payment '.$payment->invoice_number)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Payment {{ $payment->invoice_number }}</h2>
    <div>
        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-secondary"><i class="bi bi-receipt"></i> Receipt</a>
        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Payment Details</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><strong>Invoice:</strong> {{ $payment->invoice_number }}</div>
                    <div class="col-md-6 mb-3"><strong>Status:</strong> <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</span></div>
                    <div class="col-md-6 mb-3"><strong>Tenant:</strong> {{ $payment->tenant->user->name ?? 'N/A' }}</div>
                    <div class="col-md-6 mb-3"><strong>Unit:</strong> {{ $payment->unit->unit_number ?? 'N/A' }}</div>
                    <div class="col-md-4 mb-3"><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</div>
                    <div class="col-md-4 mb-3"><strong>Paid:</strong> ${{ number_format($payment->paid_amount ?? 0, 2) }}</div>
                    <div class="col-md-4 mb-3"><strong>Balance:</strong> ${{ number_format($payment->balance, 2) }}</div>
                    <div class="col-md-4 mb-3"><strong>Late Fee:</strong> ${{ number_format($payment->late_fee, 2) }}</div>
                    <div class="col-md-4 mb-3"><strong>Due Date:</strong> {{ $payment->due_date->format('M d, Y') }}</div>
                    <div class="col-md-4 mb-3"><strong>Paid Date:</strong> {{ $payment->paid_date ? $payment->paid_date->format('M d, Y') : 'N/A' }}</div>
                    <div class="col-md-4 mb-3"><strong>Method:</strong> {{ $payment->payment_method ? ucfirst(str_replace('_',' ',$payment->payment_method)) : 'N/A' }}</div>
                    <div class="col-md-4 mb-3"><strong>Mobile Money #:</strong> {{ $payment->mobile_money_number ?? 'N/A' }}</div>
                    <div class="col-md-4 mb-3"><strong>Reference:</strong> {{ $payment->transaction_reference ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($payment->status !== 'paid')
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Mark as Paid</h5></div>
            <div class="card-body">
                <form action="{{ route('payments.mark-as-paid', $payment) }}" method="POST">
                    @csrf
                    <div class="mb-2"><label class="form-label">Amount Paid</label><input type="number" name="paid_amount" class="form-control" value="{{ $payment->amount }}" step="0.01" required></div>
                    <div class="mb-2">
                        <select name="payment_method" class="form-select" id="payMethod">
                            <option value="">Method</option>
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="orange_money">Orange Money</option>
                            <option value="mtn_money">MTN Money</option>
                        </select>
                    </div>
                    <div class="mb-2" id="mobilePayField" style="display:none"><input type="text" name="mobile_money_number" class="form-control" placeholder="Mobile money number"></div>
                    <div class="mb-2"><input type="text" name="transaction_reference" class="form-control" placeholder="Reference"></div>
                    <button class="btn btn-success w-100">Mark as Paid</button>
                    <script>document.getElementById('payMethod').addEventListener('change',function(){document.getElementById('mobilePayField').style.display=this.value==='orange_money'||this.value==='mtn_money'?'block':'none';});</script>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
