@extends('layouts.app')

@section('title', 'Properties')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Properties</h2>
    <a href="{{ route('properties.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Property</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, city, address..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>City</th>
                        <th>Units</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                    <tr>
                        <td><a href="{{ route('properties.show', $property) }}">{{ $property->name }}</a></td>
                        <td>{{ ucfirst($property->type) }}</td>
                        <td>{{ $property->city }}</td>
                        <td>{{ $property->units_count ?? $property->units->count() }}</td>
                        <td>
                            <span class="badge bg-{{ $property->status === 'active' ? 'success' : ($property->status === 'inactive' ? 'secondary' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $property->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('properties.edit', $property) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('properties.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No properties found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $properties->links() }}
    </div>
</div>
@endsection
