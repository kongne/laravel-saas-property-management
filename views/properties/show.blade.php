@extends('layouts.app')

@section('title', $property->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ $property->name }}</h2>
    <div>
        <a href="{{ route('properties.edit', $property) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Property Details</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><strong>Type:</strong> {{ ucfirst($property->type) }}</div>
                    <div class="col-md-6 mb-3"><strong>Status:</strong> <span class="badge bg-{{ $property->status === 'active' ? 'success' : 'secondary' }}">{{ $property->status }}</span></div>
                    <div class="col-md-6 mb-3"><strong>Address:</strong> {{ $property->address }}</div>
                    <div class="col-md-6 mb-3"><strong>City:</strong> {{ $property->city }}, {{ $property->state }} {{ $property->zip_code }}</div>
                    <div class="col-md-6 mb-3"><strong>Country:</strong> {{ $property->country }}</div>
                    <div class="col-md-6 mb-3"><strong>Total Units:</strong> {{ $property->total_units }}</div>
                    <div class="col-md-6 mb-3"><strong>Area:</strong> {{ $property->area_sqft ? number_format($property->area_sqft, 2).' sqft' : 'N/A' }}</div>
                    <div class="col-12"><strong>Description:</strong><br>{{ $property->description ?? 'No description' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Units ({{ $property->units->count() }})</h5>
                <a href="{{ route('units.create', ['property_id' => $property->id]) }}" class="btn btn-sm btn-primary">Add Unit</a>
            </div>
            <div class="card-body">
                @if($property->units->count())
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Unit #</th><th>Type</th><th>Bed/Bath</th><th>Rent</th><th>Status</th><th>Tenant</th></tr></thead>
                        <tbody>
                            @foreach($property->units as $unit)
                            <tr>
                                <td><a href="{{ route('units.show', $unit) }}">{{ $unit->unit_number }}</a></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</td>
                                <td>{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
                                <td>${{ number_format($unit->rent_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $unit->status === 'available' ? 'success' : ($unit->status === 'occupied' ? 'primary' : 'warning') }}">{{ ucfirst($unit->status) }}</span></td>
                                <td>{{ $unit->activeTenant->user->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted">No units added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($property->images)
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Images</h5></div>
            <div class="card-body">
                @foreach($property->images as $image)
                    <img src="{{ asset('storage/'.$image) }}" class="img-fluid rounded mb-2">
                @endforeach
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Quick Stats</h5></div>
            <div class="card-body">
                <p><strong>Available:</strong> {{ $property->availableUnits->count() }}</p>
                <p><strong>Occupied:</strong> {{ $property->occupiedUnits->count() }}</p>
                <p><strong>Active Leases:</strong> {{ $property->activeLeases->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
