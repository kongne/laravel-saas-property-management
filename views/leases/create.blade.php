@extends('layouts.app')
@section('title', 'Create Lease')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Create Lease</h2>
    <a href="{{ route('leases.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('leases.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Unit *</label>
                    <select name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->unit_number }} - {{ $u->property->name }} (${{ number_format($u->rent_amount,2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tenant *</label>
                    <select name="tenant_id" class="form-select" required>
                        <option value="">Select Tenant</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}">{{ $t->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Start Date *</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required></div>
                <div class="col-md-3"><label class="form-label">End Date *</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required></div>
                <div class="col-md-3"><label class="form-label">Rent Amount *</label><input type="number" name="rent_amount" class="form-control" value="{{ old('rent_amount') }}" step="0.01" required></div>
                <div class="col-md-3"><label class="form-label">Security Deposit</label><input type="number" name="security_deposit" class="form-control" value="{{ old('security_deposit') }}" step="0.01"></div>
                <div class="col-md-4">
                    <label class="form-label">Payment Frequency</label>
                    <select name="payment_frequency" class="form-select">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Due Day of Month</label><input type="number" name="due_day" class="form-control" value="1" min="1" max="31"></div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Terms & Conditions</label><textarea name="terms" class="form-control" rows="4">{{ old('terms') }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Create Lease</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
