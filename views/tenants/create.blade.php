@extends('layouts.app')
@section('title', 'Add Tenant')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Tenant</h2>
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('tenants.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">User (Tenant Account) *</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select User</option>
                        @foreach(\App\Models\User::where('role','tenant')->get() as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Create a user account for the tenant first</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unit *</label>
                    <select name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->unit_number }} - {{ $u->property->name }} (${{ number_format($u->rent_amount,2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Emergency Contact</label><input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}"></div>
                <div class="col-md-4"><label class="form-label">Emergency Phone</label><input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}"></div>
                <div class="col-md-4"><label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Save Tenant</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
