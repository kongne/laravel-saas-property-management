@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <span class="text-muted">Welcome back, {{ $user->name }}</span>
</div>

@if($user->isAdmin() || $user->isLandlord())
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Properties</h6>
                        <h2 class="mb-0">{{ $stats['total_properties'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-building fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Units</h6>
                        <h2 class="mb-0">{{ $stats['total_units'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-door-open fs-1 opacity-50"></i>
                </div>
                <small>{{ $stats['occupied_units'] ?? 0 }} occupied / {{ $stats['available_units'] ?? 0 }} available</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tenants</h6>
                        <h2 class="mb-0">{{ $stats['total_tenants'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
                <small>{{ $stats['active_leases'] ?? 0 }} active leases</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Monthly Revenue</h6>
                        <h2 class="mb-0">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</h2>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-danger"><i class="bi bi-exclamation-triangle"></i> Overdue Payments</h6>
                <h3>{{ $stats['overdue_payments'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-warning"><i class="bi bi-clock"></i> Pending Payments</h6>
                <h3>{{ $stats['pending_payments'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-secondary"><i class="bi bi-tools"></i> Open Maintenance</h6>
                <h3>{{ $stats['open_maintenance'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Recent Notifications</h5>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @php $recentNotifications = Auth::user()->notifications()->latest()->take(5)->get(); @endphp
                @if($recentNotifications->count())
                <div class="list-group list-group-flush">
                    @foreach($recentNotifications as $notification)
                    <div class="list-group-item px-0 {{ $notification->read_at ? '' : 'fw-semibold' }}">
                        <div class="d-flex justify-content-between">
                            <small>{{ $notification->data['title'] ?? 'Notification' }}</small>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted">{{ $notification->data['message'] ?? '' }}</small>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No notifications yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($user->isTenantUser() && isset($stats['my_unit']))
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">My Unit</h5></div>
            <div class="card-body">
                @if($stats['my_unit'])
                <p><strong>Unit:</strong> {{ $stats['my_unit']->unit_number }}</p>
                <p><strong>Property:</strong> {{ $stats['my_unit']->property->name ?? 'N/A' }}</p>
                <p><strong>Rent:</strong> ${{ number_format($stats['my_unit']->rent_amount, 2) }}</p>
                @else
                <p class="text-muted">No unit assigned.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">My Lease</h5></div>
            <div class="card-body">
                @if($stats['my_lease'])
                <p><strong>From:</strong> {{ $stats['my_lease']->start_date->format('M d, Y') }}</p>
                <p><strong>To:</strong> {{ $stats['my_lease']->end_date->format('M d, Y') }}</p>
                <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($stats['my_lease']->status) }}</span></p>
                @else
                <p class="text-muted">No active lease.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Recent Payments</h5></div>
            <div class="card-body">
                @if(isset($stats['my_payments']) && $stats['my_payments']->count())
                <table class="table table-sm">
                    <thead><tr><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($stats['my_payments'] as $payment)
                        <tr>
                            <td>{{ $payment->due_date->format('M d, Y') }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td><span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">No payments yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
