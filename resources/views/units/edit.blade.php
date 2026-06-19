@extends('layouts.app')
@section('title', 'Edit Unit')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Unit</h2>
    <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('units.update', $unit) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Property</label>
                    <select name="property_id" class="form-select">
                        @foreach($properties as $p)
                            <option value="{{ $p->id }}" {{ $unit->property_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Unit Number</label><input type="text" name="unit_number" class="form-control" value="{{ $unit->unit_number }}" required></div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        @foreach(['studio','one_bedroom','two_bedroom','three_bedroom','penthouse','commercial','other'] as $t)
                            <option value="{{ $t }}" {{ $unit->type === $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Bedrooms</label><input type="number" name="bedrooms" class="form-control" value="{{ $unit->bedrooms }}"></div>
                <div class="col-md-3"><label class="form-label">Bathrooms</label><input type="number" name="bathrooms" class="form-control" value="{{ $unit->bathrooms }}"></div>
                <div class="col-md-3"><label class="form-label">Rent Amount</label><input type="number" name="rent_amount" class="form-control" value="{{ $unit->rent_amount }}" step="0.01" required></div>
                <div class="col-md-3"><label class="form-label">Security Deposit</label><input type="number" name="security_deposit" class="form-control" value="{{ $unit->security_deposit }}" step="0.01"></div>
                <div class="col-md-3"><label class="form-label">Area (sqft)</label><input type="number" name="area_sqft" class="form-control" value="{{ $unit->area_sqft }}" step="0.01"></div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['available','occupied','maintenance','reserved'] as $s)
                            <option value="{{ $s }}" {{ $unit->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ $unit->description }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Update Unit</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
