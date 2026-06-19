@extends('layouts.app')
@section('title', 'Audit Trail')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 page-header gap-2">
    <div>
        <h2 class="fw-bold mb-1">Audit Trail</h2>
        <p class="text-muted small mb-0">Track all user activity across the system</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="action" class="form-select form-select-sm">
                    <option value="">All Actions</option>
                    @foreach($actions as $a)
                    <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-nowrap small">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($log->action) }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td class="font-monospace small">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            @include('partials.empty-state', [
                                'title' => 'No activity logged yet',
                                'message' => 'Actions will be recorded here as users interact with the system.',
                            ])
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
