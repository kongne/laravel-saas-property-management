@extends('layouts.app')

@section('title', 'Edit Property')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Property</h2>
    <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Property Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $property->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select">
                        <option value="apartment" {{ $property->type === 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="house" {{ $property->type === 'house' ? 'selected' : '' }}>House</option>
                        <option value="commercial" {{ $property->type === 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="condo" {{ $property->type === 'condo' ? 'selected' : '' }}>Condo</option>
                        <option value="villa" {{ $property->type === 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="other" {{ $property->type === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $property->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $property->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="under_maintenance" {{ $property->status === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $property->description) }}</textarea>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address *</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $property->address) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $property->city) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', $property->state) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Zip Code</label>
                    <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $property->zip_code) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', $property->country) }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Property</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
