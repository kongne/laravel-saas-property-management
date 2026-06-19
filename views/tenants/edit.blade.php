@extends('layouts.app')
@section('title', 'Edit Tenant')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Tenant</h2>
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('tenants.update', $tenant) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                        @foreach(\App\Models\User::where('role','tenant')->get() as $u)
                            <option value="{{ $u->id }}" {{ $tenant->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unit</label>
                    <select name="unit_id" class="form-select">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $tenant->unit_id == $u->id ? 'selected' : '' }}>{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Emergency Contact</label><input type="text" name="emergency_contact_name" class="form-control" value="{{ $tenant->emergency_contact_name }}"></div>
                <div class="col-md-4"><label class="form-label">Emergency Phone</label><input type="text" name="emergency_contact_phone" class="form-control" value="{{ $tenant->emergency_contact_phone }}"></div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pending','active','past'] as $s)
                            <option value="{{ $s }}" {{ $tenant->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ $tenant->notes }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Update Tenant</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
