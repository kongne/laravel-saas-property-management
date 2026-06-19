@extends('layouts.app')
@section('title', __('Edit Unit'))
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Units'), 'url' => route('units.index')],
        ['label' => __('Edit Unit')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Edit Unit') }}</h2>
    <a href="{{ route('units.index') }}" class="btn-secondary btn-sm">{{ __('Back') }}</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Unit Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('units.update', $unit) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <x-forms.select name="property_id" label="{{ __('Property') }}" :options="$properties->mapWithKeys(fn($p) => [$p->id => $p->name])->toArray()" :value="old('property_id', $unit->property_id)" />
                </div>
                <div class="md:col-span-3"><x-forms.input name="unit_number" label="Unit Number" :value="old('unit_number', $unit->unit_number)" required /></div>
                <div class="md:col-span-3">
                    <x-forms.select name="type" label="{{ __('Type') }}" :options="['studio' => 'Studio', 'one_bedroom' => '1 Bedroom', 'two_bedroom' => '2 Bedroom', 'three_bedroom' => '3 Bedroom', 'penthouse' => 'Penthouse', 'commercial' => 'Commercial', 'other' => 'Other']" :value="old('type', $unit->type)" />
                </div>
                <div class="md:col-span-3"><x-forms.input name="bedrooms" label="Bedrooms" type="number" :value="old('bedrooms', $unit->bedrooms)" /></div>
                <div class="md:col-span-3"><x-forms.input name="bathrooms" label="Bathrooms" type="number" :value="old('bathrooms', $unit->bathrooms)" /></div>
                <div class="md:col-span-3"><x-forms.input name="rent_amount" label="Rent Amount" type="number" step="0.01" :value="old('rent_amount', $unit->rent_amount)" required /></div>
                <div class="md:col-span-3"><x-forms.input name="security_deposit" label="Security Deposit" type="number" step="0.01" :value="old('security_deposit', $unit->security_deposit)" /></div>
                <div class="md:col-span-3"><x-forms.input name="area_sqft" label="Area (sqft)" type="number" step="0.01" :value="old('area_sqft', $unit->area_sqft)" /></div>
                <div class="md:col-span-3">
                    <x-forms.select name="status" label="{{ __('Status') }}" :options="['available' => __('Available'), 'occupied' => __('Occupied'), 'maintenance' => __('Maintenance'), 'reserved' => __('Reserved')]" :value="old('status', $unit->status)" />
                </div>
                <div class="md:col-span-12"><x-forms.textarea name="description" label="{{ __('Description') }}" :value="old('description', $unit->description)" rows="2" /></div>
                <div class="col-span-12"><x-forms.button variant="primary">Update Unit</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection