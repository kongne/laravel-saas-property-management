@extends('layouts.app')
@section('title', 'Leases')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Leases</h2>
    <a href="{{ route('leases.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Create Lease</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search tenant name..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-outline-secondary w-100">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Tenant</th><th>Unit</th><th>Property</th><th>Period</th><th>Rent</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($leases as $lease)
                    <tr>
                        <td><a href="{{ route('leases.show', $lease) }}">{{ $lease->tenant->user->name }}</a></td>
                        <td>{{ $lease->unit->unit_number }}</td>
                        <td>{{ $lease->unit->property->name }}</td>
                        <td>{{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}</td>
                        <td>${{ number_format($lease->rent_amount, 2) }}</td>
                        <td><span class="badge bg-{{ $lease->status === 'active' ? 'success' : ($lease->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($lease->status) }}</span></td>
                        <td>
                            <a href="{{ route('leases.show', $lease) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('leases.edit', $lease) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No leases found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $leases->links() }}
    </div>
</div>
@endsection
