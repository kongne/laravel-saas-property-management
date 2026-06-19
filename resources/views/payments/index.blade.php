@extends('layouts.app')
@section('title', 'Payments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Payments</h2>
    <div>
        <span class="me-3 text-success"><strong>Collected:</strong> ${{ number_format($totalCollected, 2) }}</span>
        <span class="me-3 text-danger"><strong>Due:</strong> ${{ number_format($totalDue, 2) }}</span>
        <a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Record Payment</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3"><input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}"></div>
            <div class="col-md-3"><input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-outline-secondary w-100">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Invoice</th><th>Tenant</th><th>Unit</th><th>Amount</th><th>Paid</th><th>Due</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td><a href="{{ route('payments.show', $payment) }}">{{ $payment->invoice_number }}</a></td>
                        <td>{{ $payment->tenant->user->name ?? 'N/A' }}</td>
                        <td>{{ $payment->unit->unit_number ?? 'N/A' }}</td>
                        <td>${{ number_format($payment->amount, 2) }}</td>
                        <td>${{ number_format($payment->paid_amount ?? 0, 2) }}</td>
                        <td>{{ $payment->due_date->format('M d, Y') }}</td>
                        <td><span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'overdue' ? 'danger' : ($payment->status === 'partial' ? 'info' : 'warning')) }}">{{ ucfirst($payment->status) }}</span></td>
                        <td>
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-secondary"><i class="bi bi-receipt"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection
