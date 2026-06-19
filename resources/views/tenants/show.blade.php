@extends('layouts.app')
@section('title', $tenant->user->name)
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ $tenant->user->name }}</h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('tenants.edit', $tenant) }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors font-medium text-sm inline-flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit</a>
        <a href="{{ route('tenants.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Tenant Information</h5></div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-sm text-slate-600"><strong>Name:</strong> {{ $tenant->user->name }}</div>
                    <div class="text-sm text-slate-600"><strong>Email:</strong> {{ $tenant->user->email }}</div>
                    <div class="text-sm text-slate-600"><strong>Phone:</strong> {{ $tenant->user->phone ?? 'N/A' }}</div>
                    <div class="text-sm text-slate-600"><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $tenant->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($tenant->status) }}</span></div>
                    <div class="text-sm text-slate-600"><strong>Unit:</strong> {{ $tenant->unit->unit_number }} ({{ $tenant->unit->property->name }})</div>
                    <div class="text-sm text-slate-600"><strong>Rent:</strong> ${{ number_format($tenant->unit->rent_amount, 2) }}</div>
                    <div class="text-sm text-slate-600"><strong>Emergency Contact:</strong> {{ $tenant->emergency_contact_name ?? 'N/A' }}</div>
                    <div class="text-sm text-slate-600"><strong>Emergency Phone:</strong> {{ $tenant->emergency_contact_phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        @if($tenant->activeLease)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Active Lease</h5></div>
            <div class="p-6">
                <p class="text-sm text-slate-600"><strong>Start:</strong> {{ $tenant->activeLease->start_date->format('M d, Y') }}</p>
                <p class="text-sm text-slate-600"><strong>End:</strong> {{ $tenant->activeLease->end_date->format('M d, Y') }}</p>
                <p class="text-sm text-slate-600"><strong>Rent:</strong> ${{ number_format($tenant->activeLease->rent_amount, 2) }}/{{ $tenant->activeLease->payment_frequency }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Recent Payments</h5></div>
            <div class="p-6">
                @if($tenant->payments->count())
                <div class="divide-y divide-slate-100">
                    @foreach($tenant->payments as $p)
                    <div class="px-4 py-3 text-sm flex items-center justify-between">
                        <span class="text-slate-600">{{ $p->due_date->format('M d') }}</span>
                        <span class="text-slate-600">${{ number_format($p->amount,2) }} <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $p->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($p->status) }}</span></span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-500">No payments yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
