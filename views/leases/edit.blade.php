@extends('layouts.app')
@section('title', 'Edit Lease')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Lease</h2>
    <a href="{{ route('leases.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('leases.update', $lease) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Unit</label>
                    <select name="unit_id" class="form-select">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $lease->unit_id == $u->id ? 'selected' : '' }}>{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tenant</label>
                    <select name="tenant_id" class="form-select">
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ $lease->tenant_id == $t->id ? 'selected' : '' }}>{{ $t->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ $lease->start_date->format('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control" value="{{ $lease->end_date->format('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label">Rent Amount</label><input type="number" name="rent_amount" class="form-control" value="{{ $lease->rent_amount }}" step="0.01"></div>
                <div class="col-md-3"><label class="form-label">Security Deposit</label><input type="number" name="security_deposit" class="form-control" value="{{ $lease->security_deposit }}" step="0.01"></div>
                <div class="col-md-4">
                    <select name="payment_frequency" class="form-select">
                        @foreach(['monthly','quarterly','yearly'] as $f)
                            <option value="{{ $f }}" {{ $lease->payment_frequency === $f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Due Day</label><input type="number" name="due_day" class="form-control" value="{{ $lease->due_day }}" min="1" max="31"></div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        @foreach(['pending','active','expired','terminated'] as $s)
                            <option value="{{ $s }}" {{ $lease->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Terms</label><textarea name="terms" class="form-control" rows="3">{{ $lease->terms }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Update Lease</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
