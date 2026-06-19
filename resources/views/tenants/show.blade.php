@extends('layouts.app')
@section('title', $tenant->user->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ $tenant->user->name }}</h2>
    <div>
        <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Tenant Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><strong>Name:</strong> {{ $tenant->user->name }}</div>
                    <div class="col-md-6 mb-3"><strong>Email:</strong> {{ $tenant->user->email }}</div>
                    <div class="col-md-6 mb-3"><strong>Phone:</strong> {{ $tenant->user->phone ?? 'N/A' }}</div>
                    <div class="col-md-6 mb-3"><strong>Status:</strong> <span class="badge bg-{{ $tenant->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($tenant->status) }}</span></div>
                    <div class="col-md-6 mb-3"><strong>Unit:</strong> {{ $tenant->unit->unit_number }} ({{ $tenant->unit->property->name }})</div>
                    <div class="col-md-6 mb-3"><strong>Rent:</strong> ${{ number_format($tenant->unit->rent_amount, 2) }}</div>
                    <div class="col-md-6 mb-3"><strong>Emergency Contact:</strong> {{ $tenant->emergency_contact_name ?? 'N/A' }}</div>
                    <div class="col-md-6 mb-3"><strong>Emergency Phone:</strong> {{ $tenant->emergency_contact_phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        @if($tenant->activeLease)
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Active Lease</h5></div>
            <div class="card-body">
                <p><strong>Start:</strong> {{ $tenant->activeLease->start_date->format('M d, Y') }}</p>
                <p><strong>End:</strong> {{ $tenant->activeLease->end_date->format('M d, Y') }}</p>
                <p><strong>Rent:</strong> ${{ number_format($tenant->activeLease->rent_amount, 2) }}/{{ $tenant->activeLease->payment_frequency }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Recent Payments</h5></div>
            <div class="card-body">
                @if($tenant->payments->count())
                <ul class="list-group">
                    @foreach($tenant->payments as $p)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $p->due_date->format('M d') }}</span>
                        <span>${{ number_format($p->amount,2) }} <span class="badge bg-{{ $p->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($p->status) }}</span></span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No payments yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
