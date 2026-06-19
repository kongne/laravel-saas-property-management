@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 page-header">
    <div>
        <h2 class="fw-bold mb-1">Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, {{ $user->name }}</p>
    </div>
    <div>
        <span class="text-muted small">{{ now()->format('l, F j, Y') }}</span>
    </div>
</div>

@if($user->isAdmin() || $user->isLandlord())
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card text-bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="card-text small opacity-75 mb-1">Properties</p>
                        <h2 class="mb-0 fw-bold">{{ $stats['total_properties'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-building fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card text-bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="card-text small opacity-75 mb-1">Units</p>
                        <h2 class="mb-0 fw-bold">{{ $stats['total_units'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-door-open fs-2 opacity-50"></i>
                </div>
                <small class="opacity-75">{{ $stats['occupied_units'] ?? 0 }} occ / {{ $stats['available_units'] ?? 0 }} avl</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card text-bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="card-text small opacity-75 mb-1">Tenants</p>
                        <h2 class="mb-0 fw-bold">{{ $stats['total_tenants'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-people fs-2 opacity-50"></i>
                </div>
                <small class="opacity-75">{{ $stats['active_leases'] ?? 0 }} active leases</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card text-bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="card-text small opacity-75 mb-1">Monthly Revenue</p>
                        <h2 class="mb-0 fw-bold">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</h2>
                    </div>
                    <i class="bi bi-currency-dollar fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Revenue Overview</h5>
                <span class="badge bg-success">This Year</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><h5>Payment Status</h5></div>
            <div class="card-body">
                <div class="chart-container" style="height:200px">
                    <canvas id="paymentChart"></canvas>
                </div>
                <div class="row text-center mt-3 g-2" id="paymentLegend"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-start border-4 border-danger h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                <div>
                    <p class="text-muted small mb-0">Overdue Payments</p>
                    <h3 class="fw-bold mb-0 text-danger">{{ $stats['overdue_payments'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-4 border-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-clock-fill text-warning fs-1"></i>
                <div>
                    <p class="text-muted small mb-0">Pending Payments</p>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_payments'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-4 border-secondary h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-tools text-secondary fs-1"></i>
                <div>
                    <p class="text-muted small mb-0">Open Maintenance</p>
                    <h3 class="fw-bold mb-0">{{ $stats['open_maintenance'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    var textColor = isDark ? '#a1a1aa' : '#6c757d';

    // Revenue chart
    var revCtx = document.getElementById('revenueChart');
    if (revCtx) {
        fetch('/api/dashboard/stats', {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                new Chart(revCtx, {
                    type: 'bar',
                    data: {
                        labels: data.monthly_labels || ['Jan','Feb','Mar','Apr','May','Jun'],
                        datasets: [{
                            label: 'Revenue',
                            data: data.monthly_revenue_data || [0,0,0,0,0,0],
                            backgroundColor: isDark ? 'rgba(59,130,246,0.6)' : 'rgba(13,110,253,0.2)',
                            borderColor: isDark ? '#3b82f6' : '#0d6efd',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                            x: { grid: { display: false }, ticks: { color: textColor } }
                        }
                    }
                });
            });
    }

    // Payment status chart
    var payCtx = document.getElementById('paymentChart');
    if (payCtx) {
        fetch('/api/dashboard/stats', {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                var statuses = data.payment_statuses || {paid:0,pending:0,overdue:0,partial:0};
                var labels = Object.keys(statuses);
                var values = Object.values(statuses);
                var colors = ['#198754','#ffc107','#dc3545','#0dcaf0'];
                new Chart(payCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors.slice(0,labels.length),
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        cutout: '70%'
                    }
                });
                var legend = document.getElementById('paymentLegend');
                if (legend) {
                    var html = '';
                    labels.forEach(function(l,i){
                        html += '<div class="col-6"><small><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:'+colors[i]+';margin-right:4px"></span> '+l.charAt(0).toUpperCase()+l.slice(1)+' <strong>'+values[i]+'</strong></small></div>';
                    });
                    legend.innerHTML = html;
                }
            });
    }
});
</script>
@endpush
@endif

@if($user->isTenantUser() && isset($stats['my_unit']))
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-door-open text-primary"></i>
                <h5 class="mb-0">My Unit</h5>
            </div>
            <div class="card-body">
                @if($stats['my_unit'])
                <div class="row">
                    <div class="col-6 mb-3"><small class="text-muted d-block">Unit</small><strong>{{ $stats['my_unit']->unit_number }}</strong></div>
                    <div class="col-6 mb-3"><small class="text-muted d-block">Property</small><strong>{{ $stats['my_unit']->property->name ?? 'N/A' }}</strong></div>
                    <div class="col-6"><small class="text-muted d-block">Rent Amount</small><strong class="text-primary">${{ number_format($stats['my_unit']->rent_amount, 2) }}</strong></div>
                    <div class="col-6"><small class="text-muted d-block">Status</small><strong>{{ ucfirst($stats['my_unit']->status) }}</strong></div>
                </div>
                @else
                @include('partials.empty-state', ['message' => 'No unit assigned yet.'])
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-file-text text-success"></i>
                <h5 class="mb-0">My Lease</h5>
            </div>
            <div class="card-body">
                @if($stats['my_lease'])
                <div class="row">
                    <div class="col-6 mb-3"><small class="text-muted d-block">Start Date</small><strong>{{ $stats['my_lease']->start_date->format('M d, Y') }}</strong></div>
                    <div class="col-6 mb-3"><small class="text-muted d-block">End Date</small><strong>{{ $stats['my_lease']->end_date->format('M d, Y') }}</strong></div>
                    <div class="col-6"><small class="text-muted d-block">Status</small> <span class="badge bg-success">{{ ucfirst($stats['my_lease']->status) }}</span></div>
                    <div class="col-6"><small class="text-muted d-block">Rent</small><strong>${{ number_format($stats['my_lease']->rent_amount ?? $stats['my_unit']->rent_amount, 2) }}</strong></div>
                </div>
                @else
                @include('partials.empty-state', ['message' => 'No active lease.'])
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-credit-card text-warning"></i>
                <h5 class="mb-0">Recent Payments</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($stats['my_payments']) && $stats['my_payments']->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Date</th><th>Invoice</th><th>Amount</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($stats['my_payments'] as $payment)
                            <tr>
                                <td>{{ $payment->due_date->format('M d, Y') }}</td>
                                <td><a href="{{ route('payments.show', $payment) }}">{{ $payment->invoice_number }}</a></td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td><span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                @include('partials.empty-state', ['message' => 'No payments yet.'])
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
