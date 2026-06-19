@extends('layouts.app')
@section('title', 'New Maintenance Request')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>New Maintenance Request</h2>
    <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Unit *</label>
                    <select name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="general">General</option>
                        <option value="plumbing">Plumbing</option>
                        <option value="electrical">Electrical</option>
                        <option value="hvac">HVAC</option>
                        <option value="appliance">Appliance</option>
                        <option value="structural">Structural</option>
                        <option value="pest">Pest Control</option>
                    </select>
                </div>
                <div class="col-md-12"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="{{ old('title') }}" required></div>
                <div class="col-md-12"><label class="form-label">Description *</label><textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Assigned To</label><input type="text" name="assigned_to" class="form-control" value="{{ old('assigned_to') }}"></div>
                <div class="col-md-4"><label class="form-label">Estimated Cost</label><input type="number" name="cost" class="form-control" value="{{ old('cost') }}" step="0.01"></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Create Request</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
