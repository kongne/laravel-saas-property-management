@extends('layouts.app')
@section('title', $maintenanceRequest->title)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ $maintenanceRequest->title }}</h2>
    <div>
        <a href="{{ route('maintenance.edit', $maintenanceRequest) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <form action="{{ route('maintenance.destroy', $maintenanceRequest) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="bi bi-trash"></i></button>
        </form>
        <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Request Details</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><strong>Unit:</strong> {{ $maintenanceRequest->unit->unit_number }} ({{ $maintenanceRequest->unit->property->name }})</div>
                    <div class="col-md-4 mb-3"><strong>Priority:</strong> <span class="badge bg-{{ $maintenanceRequest->priority === 'emergency' ? 'danger' : ($maintenanceRequest->priority === 'high' ? 'warning' : 'success') }}">{{ ucfirst($maintenanceRequest->priority) }}</span></div>
                    <div class="col-md-4 mb-3"><strong>Status:</strong> <span class="badge bg-{{ $maintenanceRequest->status === 'open' ? 'danger' : ($maintenanceRequest->status === 'in_progress' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_',' ',$maintenanceRequest->status)) }}</span></div>
                    <div class="col-md-4 mb-3"><strong>Category:</strong> {{ ucfirst($maintenanceRequest->category) }}</div>
                    <div class="col-md-4 mb-3"><strong>Requested:</strong> {{ $maintenanceRequest->requested_date->format('M d, Y') }}</div>
                    <div class="col-md-4 mb-3"><strong>Resolved:</strong> {{ $maintenanceRequest->resolved_date ? $maintenanceRequest->resolved_date->format('M d, Y') : 'N/A' }}</div>
                    <div class="col-md-4 mb-3"><strong>Assigned To:</strong> {{ $maintenanceRequest->assigned_to ?? 'Unassigned' }}</div>
                    <div class="col-md-4 mb-3"><strong>Cost:</strong> ${{ number_format($maintenanceRequest->cost ?? 0, 2) }}</div>
                    <div class="col-12"><strong>Description:</strong><br>{{ $maintenanceRequest->description }}</div>
                    @if($maintenanceRequest->resolution_notes)
                    <div class="col-12 mt-3"><strong>Resolution Notes:</strong><br>{{ $maintenanceRequest->resolution_notes }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($maintenanceRequest->isOpen())
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Assign</h5></div>
            <div class="card-body">
                <form action="{{ route('maintenance.assign', $maintenanceRequest) }}" method="POST">
                    @csrf
                    <div class="mb-2"><input type="text" name="assigned_to" class="form-control" placeholder="Technician name" value="{{ $maintenanceRequest->assigned_to }}"></div>
                    <button class="btn btn-primary w-100">Assign</button>
                </form>
            </div>
        </div>
        @endif
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Resolve</h5></div>
            <div class="card-body">
                <form action="{{ route('maintenance.resolve', $maintenanceRequest) }}" method="POST">
                    @csrf
                    <div class="mb-2"><textarea name="resolution_notes" class="form-control" rows="2" placeholder="Resolution notes"></textarea></div>
                    <div class="mb-2"><input type="number" name="cost" class="form-control" placeholder="Cost" value="{{ $maintenanceRequest->cost }}" step="0.01"></div>
                    <button class="btn btn-success w-100">Mark Resolved</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
