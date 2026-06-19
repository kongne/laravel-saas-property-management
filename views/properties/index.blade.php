@extends('layouts.app')
@section('title', 'Properties')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Properties</h2>
        <p class="text-muted small mb-0">{{ $properties->total() }} total properties</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('properties.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Property</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3" id="filterForm">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, city, address..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>City</th>
                        <th>Units</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                    <tr>
                        <td><a href="{{ route('properties.show', $property) }}" class="fw-medium text-decoration-none">{{ $property->name }}</a></td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst($property->type) }}</span></td>
                        <td>{{ $property->city }}</td>
                        <td>{{ $property->units_count ?? $property->units->count() }}</td>
                        <td>
                            <span class="badge bg-{{ $property->status === 'active' ? 'success' : ($property->status === 'inactive' ? 'secondary' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $property->status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-info btn-icon"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('properties.edit', $property) }}" class="btn btn-sm btn-warning btn-icon"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('properties.destroy', $property) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" data-confirm="Delete {{ $property->name }}? This will also delete all units and leases under this property."><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No properties found',
                                'message' => request('search') || request('status') ? 'Try adjusting your filters.' : 'Get started by adding your first property.',
                                'actionUrl' => request('search') || request('status') ? route('properties.index') : route('properties.create'),
                                'actionLabel' => request('search') || request('status') ? 'Clear filters' : 'Add Property'
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                @if($properties->total() > 0)
                Showing {{ $properties->firstItem() }}-{{ $properties->lastItem() }} of {{ $properties->total() }}
                @endif
            </div>
            <div>{{ $properties->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
