@extends('layouts.app')
@section('title', 'Add Unit')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Unit</h2>
    <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('units.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Property *</label>
                    <select name="property_id" class="form-select" required>
                        <option value="">Select Property</option>
                        @foreach($properties as $p)
                            <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Unit Number *</label>
                    <input type="text" name="unit_number" class="form-control" value="{{ old('unit_number') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select">
                        <option value="studio">Studio</option>
                        <option value="one_bedroom">1 Bedroom</option>
                        <option value="two_bedroom">2 Bedroom</option>
                        <option value="three_bedroom">3 Bedroom</option>
                        <option value="penthouse">Penthouse</option>
                        <option value="commercial">Commercial</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Bedrooms</label><input type="number" name="bedrooms" class="form-control" value="0" min="0"></div>
                <div class="col-md-3"><label class="form-label">Bathrooms</label><input type="number" name="bathrooms" class="form-control" value="1" min="0"></div>
                <div class="col-md-3"><label class="form-label">Rent Amount *</label><input type="number" name="rent_amount" class="form-control" value="{{ old('rent_amount') }}" step="0.01" required></div>
                <div class="col-md-3"><label class="form-label">Security Deposit</label><input type="number" name="security_deposit" class="form-control" value="{{ old('security_deposit') }}" step="0.01"></div>
                <div class="col-md-3"><label class="form-label">Area (sqft)</label><input type="number" name="area_sqft" class="form-control" value="{{ old('area_sqft') }}" step="0.01"></div>
                <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary">Save Unit</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
