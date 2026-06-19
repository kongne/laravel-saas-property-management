@extends('layouts.app')
@section('title', 'Unit '.$unit->unit_number)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Unit {{ $unit->unit_number }}</h2>
    <div>
        <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Unit Details</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><strong>Property:</strong> {{ $unit->property->name }}</div>
                    <div class="col-md-4 mb-3"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $unit->type)) }}</div>
                    <div class="col-md-4 mb-3"><strong>Status:</strong> <span class="badge bg-{{ $unit->status === 'available' ? 'success' : 'primary' }}">{{ ucfirst($unit->status) }}</span></div>
                    <div class="col-md-3 mb-3"><strong>Bedrooms:</strong> {{ $unit->bedrooms }}</div>
                    <div class="col-md-3 mb-3"><strong>Bathrooms:</strong> {{ $unit->bathrooms }}</div>
                    <div class="col-md-3 mb-3"><strong>Rent:</strong> ${{ number_format($unit->rent_amount, 2) }}</div>
                    <div class="col-md-3 mb-3"><strong>Deposit:</strong> ${{ number_format($unit->security_deposit ?? 0, 2) }}</div>
                    <div class="col-12"><strong>Description:</strong><br>{{ $unit->description ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        @if($unit->activeTenant)
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Current Tenant</h5></div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $unit->activeTenant->user->name }}</p>
                <p><strong>Email:</strong> {{ $unit->activeTenant->user->email }}</p>
                <p><strong>Phone:</strong> {{ $unit->activeTenant->user->phone ?? 'N/A' }}</p>
                @if($unit->activeLease)
                <p><strong>Lease:</strong> {{ $unit->activeLease->start_date->format('M d, Y') }} - {{ $unit->activeLease->end_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Maintenance Requests</h5></div>
            <div class="card-body">
                @if($unit->maintenanceRequests->count())
                <ul class="list-group">
                    @foreach($unit->maintenanceRequests as $mr)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $mr->title }}</span>
                        <span class="badge bg-{{ $mr->status === 'open' ? 'danger' : ($mr->status === 'in_progress' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_',' ',$mr->status)) }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No maintenance requests.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($unit->images)
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Images</h5></div>
            <div class="card-body">@foreach($unit->images as $img)<img src="{{ asset('storage/'.$img) }}" class="img-fluid rounded mb-2">@endforeach</div>
        </div>
        @endif
    </div>
</div>
@endsection
