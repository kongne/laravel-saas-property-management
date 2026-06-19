@extends('layouts.app')
@section('title', 'Maintenance Requests')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Maintenance Requests</h2>
        <p class="text-muted small mb-0">{{ $requests->total() }} total requests</p>
    </div>
    <div><a href="{{ route('maintenance.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Request</a></div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="emergency" {{ request('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Title</th><th>Unit</th><th>Tenant</th><th>Priority</th><th>Status</th><th>Date</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                    <tr>
                        <td><a href="{{ route('maintenance.show', $r) }}" class="fw-medium text-decoration-none">{{ $r->title }}</a></td>
                        <td>{{ $r->unit->unit_number ?? 'N/A' }}</td>
                        <td>{{ $r->tenant->user->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $r->priority === 'emergency' ? 'danger' : ($r->priority === 'high' ? 'warning' : ($r->priority === 'medium' ? 'info' : 'success')) }}">
                                {{ ucfirst($r->priority) }}
                            </span>
                        </td>
                        <td><span class="badge bg-{{ $r->status === 'open' ? 'danger' : ($r->status === 'in_progress' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_',' ',$r->status)) }}</span></td>
                        <td class="text-nowrap small">{{ $r->requested_date->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('maintenance.show', $r) }}" class="btn btn-sm btn-info btn-icon"><i class="bi bi-eye"></i></a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
                                <a href="{{ route('maintenance.edit', $r) }}" class="btn btn-sm btn-warning btn-icon"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('maintenance.destroy', $r) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" data-confirm="Delete this maintenance request? This cannot be undone."><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No maintenance requests found',
                                'message' => request('status') || request('priority') ? 'Try adjusting your filters.' : 'No maintenance requests have been submitted.',
                                'actionUrl' => request('status') || request('priority') ? route('maintenance.index') : route('maintenance.create'),
                                'actionLabel' => request('status') || request('priority') ? 'Clear filters' : 'New Request'
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                @if($requests->total() > 0)
                Showing {{ $requests->firstItem() }}-{{ $requests->lastItem() }} of {{ $requests->total() }}
                @endif
            </div>
            <div>{{ $requests->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
