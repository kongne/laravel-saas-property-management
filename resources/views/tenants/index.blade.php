@extends('layouts.app')
@section('title', 'Tenants')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tenants</h2>
    <a href="{{ route('tenants.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Tenant</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="past" {{ request('status') === 'past' ? 'selected' : '' }}>Past</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-outline-secondary w-100">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Unit</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr>
                        <td><a href="{{ route('tenants.show', $tenant) }}">{{ $tenant->user->name }}</a></td>
                        <td>{{ $tenant->user->email }}</td>
                        <td>{{ $tenant->user->phone ?? '-' }}</td>
                        <td>{{ $tenant->unit->unit_number }} ({{ $tenant->unit->property->name }})</td>
                        <td><span class="badge bg-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($tenant->status) }}</span></td>
                        <td>
                            <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this tenant?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No tenants found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tenants->links() }}
    </div>
</div>
@endsection
