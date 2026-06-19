@extends('layouts.app')
@section('title', 'Units')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Units</h2>
        <p class="text-muted small mb-0">{{ $units->total() }} total units</p>
    </div>
    <div><a href="{{ route('units.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Unit</a></div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="property_id" class="form-select form-select-sm">
                    <option value="">All Properties</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search unit number or type..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Unit #</th><th>Property</th><th>Type</th><th>Bed/Bath</th><th>Rent</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td><a href="{{ route('units.show', $unit) }}" class="fw-medium text-decoration-none">{{ $unit->unit_number }}</a></td>
                        <td>{{ $unit->property->name }}</td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</span></td>
                        <td>{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
                        <td>${{ number_format($unit->rent_amount, 2) }}</td>
                        <td><span class="badge bg-{{ $unit->status === 'available' ? 'success' : ($unit->status === 'occupied' ? 'primary' : 'warning') }}">{{ ucfirst($unit->status) }}</span></td>
                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('units.show', $unit) }}" class="btn btn-sm btn-info btn-icon"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-warning btn-icon"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" data-confirm="Delete unit {{ $unit->unit_number }}? This cannot be undone."><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No units found',
                                'message' => request('search') || request('status') || request('property_id') ? 'Try adjusting your filters.' : 'No units have been added yet.',
                                'actionUrl' => request('search') || request('status') || request('property_id') ? route('units.index') : route('units.create'),
                                'actionLabel' => request('search') || request('status') || request('property_id') ? 'Clear filters' : 'Add Unit'
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                @if($units->total() > 0)
                Showing {{ $units->firstItem() }}-{{ $units->lastItem() }} of {{ $units->total() }}
                @endif
            </div>
            <div>{{ $units->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
