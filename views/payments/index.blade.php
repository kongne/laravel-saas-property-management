@extends('layouts.app')
@section('title', 'Payments')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Payments</h2>
        <p class="text-muted small mb-0">{{ $payments->total() }} total payments</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-success fs-6 px-3 py-2">Collected: ${{ number_format($totalCollected, 2) }}</span>
        <span class="badge bg-danger fs-6 px-3 py-2">Due: ${{ number_format($totalDue, 2) }}</span>
        @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download"></i> Export</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('payments.export', array_merge(request()->query(), ['format' => 'csv'])) }}"><i class="bi bi-filetype-csv me-2"></i>CSV</a></li>
            </ul>
        </div>
        <a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Record Payment</a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3" id="filterForm">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="check" {{ request('payment_method') === 'check' ? 'selected' : '' }}>Check</option>
                    <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="mobile_money" {{ request('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="orange_money" {{ request('payment_method') === 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                    <option value="mtn_money" {{ request('payment_method') === 'mtn_money' ? 'selected' : '' }}>MTN Money</option>
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-md-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search invoice..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="paymentsTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Invoice</th>
                        <th>Tenant</th>
                        <th>Unit</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td><input type="checkbox" class="select-row" value="{{ $payment->id }}"></td>
                        <td><a href="{{ route('payments.show', $payment) }}" class="fw-medium text-decoration-none">{{ $payment->invoice_number }}</a></td>
                        <td>{{ $payment->tenant->user->name ?? 'N/A' }}</td>
                        <td>{{ $payment->unit->unit_number ?? 'N/A' }}</td>
                        <td>${{ number_format($payment->amount, 2) }}</td>
                        <td>${{ number_format($payment->paid_amount ?? 0, 2) }}</td>
                        <td>{{ $payment->due_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'overdue' ? 'danger' : ($payment->status === 'partial' ? 'info' : 'warning')) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info btn-icon"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-secondary btn-icon"><i class="bi bi-receipt"></i></a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
                                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-warning btn-icon"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" data-confirm="Delete payment {{ $payment->invoice_number }}? This cannot be undone."><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No payments found',
                                'message' => request('search') || request('status') ? 'Try adjusting your filters.' : 'No payments have been recorded yet.',
                                'actionUrl' => request('search') || request('status') ? route('payments.index') : route('payments.create'),
                                'actionLabel' => request('search') || request('status') ? 'Clear filters' : 'Record Payment'
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                @if($payments->total() > 0)
                Showing {{ $payments->firstItem() }}-{{ $payments->lastItem() }} of {{ $payments->total() }}
                @endif
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" style="width:auto" onchange="var u=new URL(window.location.href); u.searchParams.set('per_page',this.value); window.location.href=u.href;">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10/page</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25/page</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50/page</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100/page</option>
                    </select>
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.select-row').forEach(function(cb) { cb.checked = this.checked; }, this);
});
</script>
@endpush
@endsection
