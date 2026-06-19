@extends('layouts.app')
@section('title', 'Maintenance Requests')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Maintenance Requests</h2>
    <a href="{{ route('maintenance.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Request</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="priority" class="form-select">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="emergency" {{ request('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-outline-secondary w-100">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Title</th><th>Unit</th><th>Priority</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($requests as $r)
                    <tr>
                        <td><a href="{{ route('maintenance.show', $r) }}">{{ $r->title }}</a></td>
                        <td>{{ $r->unit->unit_number }} ({{ $r->unit->property->name }})</td>
                        <td><span class="badge bg-{{ $r->priority === 'emergency' ? 'danger' : ($r->priority === 'high' ? 'warning' : 'success') }}">{{ ucfirst($r->priority) }}</span></td>
                        <td><span class="badge bg-{{ $r->status === 'open' ? 'danger' : ($r->status === 'in_progress' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_',' ',$r->status)) }}</span></td>
                        <td>{{ $r->requested_date->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('maintenance.show', $r) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('maintenance.edit', $r) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No maintenance requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $requests->links() }}
    </div>
</div>
@endsection
