@extends('layouts.app')
@section('title', __('Add Unit'))
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Units'), 'url' => route('units.index')],
        ['label' => __('Add Unit')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Add Unit') }}</h2>
    <a href="{{ route('units.index') }}" class="btn-secondary btn-sm">{{ __('Back') }}</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Unit Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('units.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <x-forms.select name="property_id" label="{{ __('Property') }}" required :options="$properties->mapWithKeys(fn($p) => [$p->id => $p->name])->toArray()" placeholder="Select Property" />
                </div>
                <div class="md:col-span-3"><x-forms.input name="unit_number" label="Unit Number" required /></div>
                <div class="md:col-span-3">
                    <x-forms.select name="type" label="{{ __('Type') }}" :options="['studio' => 'Studio', 'one_bedroom' => '1 Bedroom', 'two_bedroom' => '2 Bedroom', 'three_bedroom' => '3 Bedroom', 'penthouse' => 'Penthouse', 'commercial' => 'Commercial', 'other' => 'Other']" :value="old('type', 'studio')" />
                </div>
                <div class="md:col-span-3"><x-forms.input name="bedrooms" label="Bedrooms" type="number" :value="old('bedrooms', 0)" min="0" /></div>
                <div class="md:col-span-3"><x-forms.input name="bathrooms" label="Bathrooms" type="number" :value="old('bathrooms', 1)" min="0" /></div>
                <div class="md:col-span-3"><x-forms.input name="rent_amount" label="Rent Amount" type="number" step="0.01" required /></div>
                <div class="md:col-span-3"><x-forms.input name="security_deposit" label="Security Deposit" type="number" step="0.01" /></div>
                <div class="md:col-span-3"><x-forms.input name="area_sqft" label="Area (sqft)" type="number" step="0.01" /></div>
                <div class="md:col-span-12"><x-forms.textarea name="description" label="{{ __('Description') }}" rows="2" /></div>
                <div class="col-span-12"><x-forms.button variant="primary">Save Unit</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection