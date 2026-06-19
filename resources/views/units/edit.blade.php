@extends('layouts.app')
@section('title', 'Edit Unit')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Unit</h2>
    <a href="{{ route('units.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('units.update', $unit) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Property</label>
                    <select name="property_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($properties as $p)
                            <option value="{{ $p->id }}" {{ $unit->property_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-slate-700 mb-1.5">Unit Number</label><input type="text" name="unit_number" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $unit->unit_number }}" required></div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Type</label>
                    <select name="type" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['studio','one_bedroom','two_bedroom','three_bedroom','penthouse','commercial','other'] as $t)
                            <option value="{{ $t }}" {{ $unit->type === $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-slate-700 mb-1.5">Bedrooms</label><input type="number" name="bedrooms" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $unit->bedrooms }}"></div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-slate-700 mb-1.5">Bathrooms</label><input type="number" name="bathrooms" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $unit->bathrooms }}"></div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-slate-700 mb-1.5">Rent Amount</label><input type="number" name="rent_amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $unit->rent_amount }}" step="0.01" required></div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-slate-700 mb-1.5">Security Deposit</label><input type="number" name="security_deposit" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $unit->security_deposit }}" step="0.01"></div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-slate-700 mb-1.5">Area (sqft)</label><input type="number" name="area_sqft" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $unit->area_sqft }}" step="0.01"></div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['available','occupied','maintenance','reserved'] as $s)
                            <option value="{{ $s }}" {{ $unit->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-12"><label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label><textarea name="description" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="2">{{ $unit->description }}</textarea></div>
                <div class="col-span-12"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Update Unit</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
