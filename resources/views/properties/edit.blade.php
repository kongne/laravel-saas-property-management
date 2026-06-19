@extends('layouts.app')

@section('title', __('Edit Property'))

@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Properties'), 'url' => route('properties.index')],
        ['label' => __('Edit Property')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ __('Edit Property') }}</h1>
    <a href="{{ route('properties.show', $property) }}" class="btn-secondary btn-sm">{{ __('Cancel') }}</a>
</div>

<x-breadcrumbs :items="[['label' => __('Properties'), 'url' => route('properties.index')], ['label' => $property->name, 'url' => route('properties.show', $property)], ['label' => __('Edit')]]" />

<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">{{ $property->name }}</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <x-forms.input name="name" label="{{ __('Property Name') }}" :value="old('name', $property->name)" required />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="type" label="{{ __('Type') }}" required :options="\App\Models\Property::propertyTypes()" :value="old('type', $property->type)" placeholder="Select type..." />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="status" label="{{ __('Status') }}" :options="['active' => __('Active'), 'inactive' => __('Inactive'), 'under_maintenance' => __('Under Maintenance')]" :value="old('status', $property->status)" />
                </div>

                <div class="md:col-span-12">
                    <x-forms.textarea name="description" label="{{ __('Description') }}" :value="old('description', $property->description)" rows="4" />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Location</h4>
                </div>

                <div class="md:col-span-6">
                    <x-forms.input name="address" label="{{ __('Address') }}" :value="old('address', $property->address)" required />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="city" label="{{ __('City') }}" required :options="\App\Models\Property::cities()" :value="old('city', $property->city)" placeholder="Select city..." x-data @change="const d = document.querySelector('[name=district]'); if(d) { fetch('/api/locations/districts?city='+encodeURIComponent($el.value)).then(r=>r.json()).then(data=>{ d.innerHTML='<option value=\"\">Select district...</option>'+data.map(x=>'<option value=\"'+x+'\">'+x+'</option>').join(''); }).catch(()=>{}); }" />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="district" label="{{ __('District') }}" :options="$property->district ? [$property->district => $property->district] : []" :value="old('district', $property->district)" placeholder="Select district..." />
                </div>

                <div class="md:col-span-4">
                    <x-forms.input name="state" label="State/Region" :value="old('state', $property->state)" placeholder="e.g. Littoral" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="country" label="{{ __('Country') }}" :value="old('country', $property->country ?? 'Cameroon')" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="zip_code" label="Zip/Postal Code" :value="old('zip_code', $property->zip_code)" />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">{{ __('Details') }}</h4>
                </div>

                <div class="md:col-span-4">
                    <x-forms.input name="total_units" label="{{ __('Total Units') }}" type="number" :value="old('total_units', $property->total_units)" min="1" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="area_sqft" label="Area (sqft)" type="number" :value="old('area_sqft', $property->area_sqft)" step="0.01" min="0" />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">{{ __('Amenities') }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(\App\Models\Property::amenityOptions() as $val => $label)
                        @php $checked = in_array($val, old('amenities', $property->amenities ?? [])); @endphp
                        <x-forms.checkbox name="amenities" :value="$val" :checked="$checked" class="p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">{{ $label }}</x-forms.checkbox>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Photos & Documents</h4>
                </div>

                <div class="md:col-span-6">
                    <x-forms.group label="{{ __('Property Images') }}">
                        @if($property->images)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
                            @foreach($property->images as $img)
                            <div class="aspect-square rounded-lg overflow-hidden border border-slate-200">
                                <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <input type="file" name="images[]" multiple accept="image/*" class="input">
                        <p class="text-xs text-slate-400 mt-1">Leave empty to keep existing images.</p>
                    </x-forms.group>
                </div>

                <div class="md:col-span-6">
                    <x-forms.group label="Documents">
                        <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx" class="input">
                        <p class="text-xs text-slate-400 mt-1">Leave empty to keep existing documents.</p>
                    </x-forms.group>
                </div>

                <div class="md:col-span-6">
                    <x-forms.input name="video_url" label="Video Tour URL" type="url" :value="old('video_url', $property->video_url)" placeholder="https://youtube.com/watch?v=..." />
                </div>

                <div class="md:col-span-6 flex items-end">
                    <x-forms.checkbox name="featured" :checked="old('featured', $property->featured)">{{ __('Mark as Featured Property') }}</x-forms.checkbox>
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4 flex items-center gap-3">
                    <x-forms.button variant="primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Update Property
                    </x-forms.button>
                    <a href="{{ route('properties.show', $property) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection