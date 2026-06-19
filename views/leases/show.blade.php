@extends('layouts.app')
@section('title', 'Lease Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Lease Details</h2>
    <div>
        <a href="{{ route('leases.edit', $lease) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('leases.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Lease Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><strong>Tenant:</strong> {{ $lease->tenant->user->name }}</div>
                    <div class="col-md-6 mb-3"><strong>Unit:</strong> {{ $lease->unit->unit_number }} ({{ $lease->unit->property->name }})</div>
                    <div class="col-md-4 mb-3"><strong>Start:</strong> {{ $lease->start_date->format('M d, Y') }}</div>
                    <div class="col-md-4 mb-3"><strong>End:</strong> {{ $lease->end_date->format('M d, Y') }}</div>
                    <div class="col-md-4 mb-3"><strong>Status:</strong> <span class="badge bg-{{ $lease->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($lease->status) }}</span></div>
                    <div class="col-md-4 mb-3"><strong>Rent:</strong> ${{ number_format($lease->rent_amount, 2) }}/{{ $lease->payment_frequency }}</div>
                    <div class="col-md-4 mb-3"><strong>Deposit:</strong> ${{ number_format($lease->security_deposit ?? 0, 2) }}</div>
                    <div class="col-md-4 mb-3"><strong>Due Day:</strong> {{ $lease->due_day }} of month</div>
                    @if($lease->terms)
                    <div class="col-12"><strong>Terms:</strong><br>{{ $lease->terms }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Payments</h5>
                <a href="{{ route('payments.create', ['lease_id' => $lease->id]) }}" class="btn btn-sm btn-primary">Record Payment</a>
            </div>
            <div class="card-body">
                @if($lease->payments->count())
                <table class="table table-sm">
                    <thead><tr><th>Invoice</th><th>Due</th><th>Amount</th><th>Paid</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($lease->payments as $p)
                        <tr>
                            <td>{{ $p->invoice_number }}</td>
                            <td>{{ $p->due_date->format('M d, Y') }}</td>
                            <td>${{ number_format($p->amount,2) }}</td>
                            <td>${{ number_format($p->paid_amount ?? 0,2) }}</td>
                            <td><span class="badge bg-{{ $p->status === 'paid' ? 'success' : ($p->status === 'overdue' ? 'danger' : 'warning') }}">{{ ucfirst($p->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">No payments recorded.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Actions</h5></div>
            <div class="card-body">
                @if($lease->status === 'active')
                <form action="{{ route('leases.terminate', $lease) }}" method="POST" class="mb-2" onsubmit="return confirm('Terminate this lease?')">
                    @csrf
                    <button class="btn btn-danger w-100">Terminate Lease</button>
                </form>
                @endif
                <form action="{{ route('leases.renew', $lease) }}" method="POST">
                    @csrf
                    <div class="mb-2"><label class="form-label">New End Date</label><input type="date" name="end_date" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="form-label">New Rent</label><input type="number" name="rent_amount" class="form-control form-control-sm" step="0.01" value="{{ $lease->rent_amount }}" required></div>
                    <button class="btn btn-success w-100">Renew Lease</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
