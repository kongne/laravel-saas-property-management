@extends('layouts.app')
@section('title', 'Leases')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Leases</h2>
        <p class="text-muted small mb-0">{{ $leases->total() }} total leases</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('leases.export', ['format' => 'csv']) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Export</a>
        <a href="{{ route('leases.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Create Lease</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search tenant name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('leases.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Tenant</th><th>Unit</th><th>Property</th><th>Period</th><th>Rent</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($leases as $lease)
                    <tr>
                        <td><a href="{{ route('leases.show', $lease) }}" class="fw-medium text-decoration-none">{{ $lease->tenant->user->name }}</a></td>
                        <td>{{ $lease->unit->unit_number }}</td>
                        <td>{{ $lease->unit->property->name }}</td>
                        <td class="text-nowrap small">{{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}</td>
                        <td>${{ number_format($lease->rent_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $lease->status === 'active' ? 'success' : ($lease->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('leases.show', $lease) }}" class="btn btn-sm btn-info btn-icon"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('leases.edit', $lease) }}" class="btn btn-sm btn-warning btn-icon"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('leases.destroy', $lease) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" data-confirm="Delete lease for {{ $lease->tenant->user->name }}? This action cannot be undone."><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No leases found',
                                'message' => request('search') || request('status') ? 'Try adjusting your filters.' : 'No leases have been created yet.',
                                'actionUrl' => request('search') || request('status') ? route('leases.index') : route('leases.create'),
                                'actionLabel' => request('search') || request('status') ? 'Clear filters' : 'Create Lease'
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                @if($leases->total() > 0)
                Showing {{ $leases->firstItem() }}-{{ $leases->lastItem() }} of {{ $leases->total() }}
                @endif
            </div>
            <div>{{ $leases->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
