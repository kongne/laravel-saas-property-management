@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-sm text-slate-500">Welcome back, {{ $user->name }}</p>
</div>

@if($user->isAdmin() || $user->isLandlord())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-medium text-slate-500">Properties</p>
                    <a href="{{ route('properties.create') }}" class="text-indigo-500 hover:text-indigo-700 transition-colors" title="Add property">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_properties'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
    </div>
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-medium text-slate-500">Total Units</p>
                    <a href="{{ route('units.create') }}" class="text-emerald-500 hover:text-emerald-700 transition-colors" title="Add unit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_units'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-1">{{ $stats['occupied_units'] ?? 0 }} occupied / {{ $stats['available_units'] ?? 0 }} available</p>
    </div>
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-medium text-slate-500">Tenants</p>
                    <a href="{{ route('tenants.create') }}" class="text-amber-500 hover:text-amber-700 transition-colors" title="Add tenant">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_tenants'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-1">{{ $stats['active_leases'] ?? 0 }} active leases</p>
    </div>
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Monthly Revenue</p>
                <p class="text-2xl font-bold text-slate-800">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-red-500">Overdue Payments</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['overdue_payments'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
    </div>
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-amber-500">Pending Payments</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['pending_payments'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="card card-hover p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Open Maintenance</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['open_maintenance'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div x-data="{ chartReady: false }" x-init="$nextTick(() => {
    chartReady = true;
    new Chart($refs.revenueChart, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Revenue',
                data: [1200, 1900, 3000, 5000, 2300, 3400, 4500, 3200, 2800, 3900, 4100, 3600],
                backgroundColor: 'rgba(99,102,241,0.5)',
                borderColor: 'rgb(99,102,241)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
        }
    });
    new Chart($refs.statusChart, {
        type: 'doughnut',
        data: {
            labels: ['Paid','Pending','Overdue'],
            datasets: [{
                data: [{{ $stats['monthly_revenue'] ?? 0 }}, {{ $stats['pending_payments'] ?? 0 }}, {{ $stats['overdue_payments'] ?? 0 }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
})">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="card card-hover p-6">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500 mb-4">Monthly Revenue</h3>
            <div class="h-64 sm:h-72" x-show="!chartReady">
                <x-skeleton type="chart" />
            </div>
            <div class="h-64 sm:h-72" x-show="chartReady" style="display: none;"><canvas x-ref="revenueChart"></canvas></div>
        </div>
        <div class="card card-hover p-6">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500 mb-4">Payment Status</h3>
            <div class="h-64 sm:h-72" x-show="!chartReady">
                <x-skeleton type="card" />
            </div>
            <div class="h-64 sm:h-72" x-show="chartReady" style="display: none;"><canvas x-ref="statusChart"></canvas></div>
        </div>
    </div>
</div>
@endif

<div class="card card-hover mb-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Recent Notifications</h3>
        </div>
        <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</a>
    </div>
    <div class="p-6">
        @php $recentNotifications = Auth::user()->notifications()->latest()->take(5)->get(); @endphp
        @if($recentNotifications->count())
        <div class="divide-y divide-slate-100">
            @foreach($recentNotifications as $notification)
            <div class="py-3 {{ $notification->read_at ? '' : 'bg-indigo-50/50 -mx-6 px-6' }}">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-800 {{ $notification->read_at ? 'font-normal' : '' }}">{{ $notification->data['title'] ?? 'Notification' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(!$notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium whitespace-nowrap">Mark read</button>
                        </form>
                        @endif
                        <span class="text-xs text-slate-400 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-6">
            <x-empty-state title="No notifications" message="Notifications will appear here when something happens." />
        </div>
        @endif
    </div>
</div>

@if($user->isAdmin() || $user->isLandlord())
<div class="card card-hover mb-6">
    <div class="card-header">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Quick Actions</h3>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('properties.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-200 dark:hover:border-indigo-700 transition-colors no-underline group">
                <svg class="w-6 h-6 text-indigo-500 group-hover:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600">Add Property</span>
            </a>
            <a href="{{ route('tenants.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:border-amber-200 dark:hover:border-amber-700 transition-colors no-underline group">
                <svg class="w-6 h-6 text-amber-500 group-hover:text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400 group-hover:text-amber-600">Add Tenant</span>
            </a>
            <a href="{{ route('payments.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:border-rose-200 dark:hover:border-rose-700 transition-colors no-underline group">
                <svg class="w-6 h-6 text-rose-500 group-hover:text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400 group-hover:text-rose-600">Record Payment</span>
            </a>
            <a href="{{ route('maintenance.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700 transition-colors no-underline group">
                <svg class="w-6 h-6 text-cyan-500 group-hover:text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400 group-hover:text-cyan-600">New Request</span>
            </a>
        </div>
    </div>
</div>
@endif

@if($user->isTenantUser() && isset($stats['my_unit']))
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card card-hover">
        <div class="card-header">
            <h3 class="text-sm font-semibold text-slate-800">My Unit</h3>
        </div>
        <div class="p-6 space-y-3">
            @if($stats['my_unit'])
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Unit:</span>
                <span class="font-medium text-slate-800">{{ $stats['my_unit']->unit_number }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Property:</span>
                <span class="font-medium text-slate-800">{{ $stats['my_unit']->property->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Rent:</span>
                <span class="font-medium text-slate-800">${{ number_format($stats['my_unit']->rent_amount, 2) }}</span>
            </div>
            @else
            <p class="text-sm text-slate-500">No unit assigned.</p>
            @endif
        </div>
    </div>
    <div class="card card-hover">
        <div class="card-header">
            <h3 class="text-sm font-semibold text-slate-800">My Lease</h3>
        </div>
        <div class="p-6 space-y-3">
            @if($stats['my_lease'])
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">From:</span>
                <span class="font-medium text-slate-800">{{ $stats['my_lease']->start_date->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">To:</span>
                <span class="font-medium text-slate-800">{{ $stats['my_lease']->end_date->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">{{ ucfirst($stats['my_lease']->status) }}</span>
            </div>
            @else
            <p class="text-sm text-slate-500">No active lease.</p>
            @endif
        </div>
    </div>
</div>
<div class="card card-hover mb-6">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Recent Payments</h3>
        <a href="{{ route('payments.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</a>
    </div>
    <div class="p-6">
        @if(isset($stats['my_payments']) && $stats['my_payments']->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left font-medium text-slate-500 pb-3">Date</th>
                        <th class="text-left font-medium text-slate-500 pb-3">Amount</th>
                        <th class="text-left font-medium text-slate-500 pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($stats['my_payments'] as $payment)
                    <tr>
                        <td class="py-3 text-slate-800">{{ $payment->due_date->format('M d, Y') }}</td>
                        <td class="py-3 text-slate-800">${{ number_format($payment->amount, 2) }}</td>
                        <td class="py-3">
                            @if($payment->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Paid</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-slate-500">No payments yet.</p>
        @endif
    </div>
</div>
@endif
@endsection
