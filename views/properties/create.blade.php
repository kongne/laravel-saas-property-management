@extends('layouts.app')

@section('title', 'Add Property')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Property</h2>
    <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Property Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select...</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="commercial">Commercial</option>
                        <option value="condo">Condo</option>
                        <option value="villa">Villa</option>
                        <option value="other">Other</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="under_maintenance">Under Maintenance</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address *</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Zip Code</label>
                    <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', 'US') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total Units</label>
                    <input type="number" name="total_units" class="form-control" value="{{ old('total_units', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Area (sqft)</label>
                    <input type="number" name="area_sqft" class="form-control" value="{{ old('area_sqft') }}" step="0.01">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Images</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Save Property</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
