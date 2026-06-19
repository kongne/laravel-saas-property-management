@extends('layouts.app')
@section('title', 'Edit Maintenance Request')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Request</h2>
    <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('maintenance.update', $maintenanceRequest) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <select name="unit_id" class="form-select">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $maintenanceRequest->unit_id == $u->id ? 'selected' : '' }}>{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-select">
                        @foreach(['low','medium','high','emergency'] as $p)
                            <option value="{{ $p }}" {{ $maintenanceRequest->priority === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        @foreach(['open','in_progress','resolved','closed'] as $s)
                            <option value="{{ $s }}" {{ $maintenanceRequest->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12"><label class="form-label">Title</label><input type="text" name="title" class="form-control" value="{{ $maintenanceRequest->title }}"></div>
                <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ $maintenanceRequest->description }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Update</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
