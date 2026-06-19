@extends('layouts.app')
@section('title', 'Tenants')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Tenants</h2>
        <p class="text-muted small mb-0">{{ $tenants->total() }} total tenants</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tenants.export', ['format' => 'csv']) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Export</a>
        <a href="{{ route('tenants.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Tenant</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="past" {{ request('status') === 'past' ? 'selected' : '' }}>Past</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Phone</th><th>Unit</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr>
                        <td><a href="{{ route('tenants.show', $tenant) }}" class="fw-medium text-decoration-none">{{ $tenant->user->name }}</a></td>
                        <td>{{ $tenant->user->email }}</td>
                        <td>{{ $tenant->user->phone ?? '-' }}</td>
                        <td>{{ $tenant->unit->unit_number ?? 'N/A' }} ({{ $tenant->unit->property->name ?? 'N/A' }})</td>
                        <td>
                            <span class="badge bg-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-sm btn-info btn-icon"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-sm btn-warning btn-icon"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" data-confirm="Remove {{ $tenant->user->name }} as tenant? This action cannot be undone."><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No tenants found',
                                'message' => request('search') || request('status') ? 'Try adjusting your filters.' : 'No tenants have been added yet.',
                                'actionUrl' => request('search') || request('status') ? route('tenants.index') : route('tenants.create'),
                                'actionLabel' => request('search') || request('status') ? 'Clear filters' : 'Add Tenant'
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                @if($tenants->total() > 0)
                Showing {{ $tenants->firstItem() }}-{{ $tenants->lastItem() }} of {{ $tenants->total() }}
                @endif
            </div>
            <div>{{ $tenants->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
