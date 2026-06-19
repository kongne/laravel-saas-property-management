@extends('layouts.app')

@section('title', 'Edit Property')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Property</h2>
    <a href="{{ route('properties.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Property Name *</label>
                    <input type="text" name="name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ old('name', $property->name) }}" required>
                    @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Type *</label>
                    <select name="type" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="apartment" {{ $property->type === 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="house" {{ $property->type === 'house' ? 'selected' : '' }}>House</option>
                        <option value="commercial" {{ $property->type === 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="condo" {{ $property->type === 'condo' ? 'selected' : '' }}>Condo</option>
                        <option value="villa" {{ $property->type === 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="other" {{ $property->type === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="active" {{ $property->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $property->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="under_maintenance" {{ $property->status === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>
                <div class="md:col-span-12">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="3">{{ old('description', $property->description) }}</textarea>
                </div>
                <div class="md:col-span-8">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Address *</label>
                    <input type="text" name="address" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('address', $property->address) }}" required>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">City *</label>
                    <input type="text" name="city" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('city', $property->city) }}" required>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">State</label>
                    <input type="text" name="state" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('state', $property->state) }}">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Zip Code</label>
                    <input type="text" name="zip_code" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('zip_code', $property->zip_code) }}">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Country</label>
                    <input type="text" name="country" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('country', $property->country) }}">
                </div>
                <div class="col-span-12">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Update Property</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
