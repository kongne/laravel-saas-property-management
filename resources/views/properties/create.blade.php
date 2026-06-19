@extends('layouts.app')

@section('title', 'Add Property')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Add Property</h2>
    <a href="{{ route('properties.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Property Name *</label>
                    <input type="text" name="name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Type *</label>
                    <select name="type" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" required>
                        <option value="">Select...</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="commercial">Commercial</option>
                        <option value="condo">Condo</option>
                        <option value="villa">Villa</option>
                        <option value="other">Other</option>
                    </select>
                    @error('type')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="under_maintenance">Under Maintenance</option>
                    </select>
                </div>
                <div class="md:col-span-12">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="md:col-span-8">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Address *</label>
                    <input type="text" name="address" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('address') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ old('address') }}" required>
                    @error('address')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">City *</label>
                    <input type="text" name="city" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('city') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ old('city') }}" required>
                    @error('city')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">State</label>
                    <input type="text" name="state" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('state') }}">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Zip Code</label>
                    <input type="text" name="zip_code" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('zip_code') }}">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Country</label>
                    <input type="text" name="country" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('country', 'US') }}">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Total Units</label>
                    <input type="number" name="total_units" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('total_units', 0) }}" min="0">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Area (sqft)</label>
                    <input type="number" name="area_sqft" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('area_sqft') }}" step="0.01">
                </div>
                <div class="md:col-span-12">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Images</label>
                    <input type="file" name="images[]" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" multiple accept="image/*">
                </div>
                <div class="col-span-12">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Save Property</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
