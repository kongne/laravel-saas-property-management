@extends('layouts.app')

@section('title', 'Units')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Units</h2>
    <a href="{{ route('units.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Unit</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <select name="property_id" class="form-select">
                    <option value="">All Properties</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search unit number or type..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Unit #</th><th>Property</th><th>Type</th><th>Bed/Bath</th><th>Rent</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td><a href="{{ route('units.show', $unit) }}">{{ $unit->unit_number }}</a></td>
                        <td>{{ $unit->property->name }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</td>
                        <td>{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
                        <td>${{ number_format($unit->rent_amount, 2) }}</td>
                        <td><span class="badge bg-{{ $unit->status === 'available' ? 'success' : ($unit->status === 'occupied' ? 'primary' : 'warning') }}">{{ ucfirst($unit->status) }}</span></td>
                        <td>
                            <a href="{{ route('units.show', $unit) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No units found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $units->links() }}
    </div>
</div>
@endsection
