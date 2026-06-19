@extends('layouts.app')

@section('title', 'Add Property')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Add Property</h1>
    <a href="{{ route('properties.index') }}" class="btn-secondary btn-sm">Back</a>
</div>

<x-breadcrumbs :items="[['label' => 'Properties', 'url' => route('properties.index')], ['label' => 'Add Property']]" />

<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Property Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <x-forms.input name="name" label="Property Name" :value="old('name')" required />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="type" label="Type" required :options="\App\Models\Property::propertyTypes()" placeholder="Select type..." x-data @change="if($el.value) { const city = document.querySelector('[name=city]'); if(city && city.value) { const evt = new Event('change', {bubbles: true}); city.dispatchEvent(evt); } }" />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="status" label="Status" :options="['active' => 'Active', 'inactive' => 'Inactive', 'under_maintenance' => 'Under Maintenance']" :value="old('status', 'active')" />
                </div>

                <div class="md:col-span-12">
                    <x-forms.textarea name="description" label="Description" :value="old('description')" rows="4" />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Location</h4>
                </div>

                <div class="md:col-span-6">
                    <x-forms.input name="address" label="Address" :value="old('address')" required />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="city" label="City" required :options="\App\Models\Property::cities()" placeholder="Select city..." x-data @change="const d = document.querySelector('[name=district]'); if(d) { fetch('/api/locations/districts?city='+encodeURIComponent($el.value)).then(r=>r.json()).then(data=>{ d.innerHTML='<option value=\"\">Select district...</option>'+data.map(x=>'<option value=\"'+x+'\">'+x+'</option>').join(''); }).catch(()=>{}); }" />
                </div>
                <div class="md:col-span-3">
                    <x-forms.select name="district" label="District" :options="[]" placeholder="Select district..." />
                </div>

                <div class="md:col-span-4">
                    <x-forms.input name="state" label="State/Region" :value="old('state')" placeholder="e.g. Littoral" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="country" label="Country" :value="old('country', 'Cameroon')" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="zip_code" label="Zip/Postal Code" :value="old('zip_code')" />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Details</h4>
                </div>

                <div class="md:col-span-4">
                    <x-forms.input name="total_units" label="Total Units" type="number" :value="old('total_units', 1)" min="1" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="area_sqft" label="Area (sqft)" type="number" :value="old('area_sqft')" step="0.01" min="0" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.input name="price" label="Price (FCFA / month)" type="number" :value="old('price')" step="0.01" min="0" />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Amenities</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(\App\Models\Property::amenityOptions() as $val => $label)
                        <x-forms.checkbox name="amenities" :value="$val" :checked="in_array($val, old('amenities', []))" class="p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">{{ $label }}</x-forms.checkbox>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Photos & Documents</h4>
                </div>

                <div class="md:col-span-6">
                    <x-forms.group label="Property Images">
                        <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors" x-data="{ dragging: false }" @@dragover.prevent="dragging = true" @@dragleave.prevent="dragging = false" @@drop.prevent="dragging = false">
                            <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm text-slate-500 mb-1">Drop images here or click to browse</p>
                            <p class="text-xs text-slate-400">JPG, PNG or GIF up to 5MB each</p>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="imageInput">
                            <button type="button" @@click="$refs.imageInput.click()" class="mt-3 btn-secondary btn-sm">Browse Files</button>
                        </div>
                        <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3"></div>
                    </x-forms.group>
                </div>

                <div class="md:col-span-6">
                    <x-forms.group label="Documents (PDF, DOC)">
                        <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx" class="input">
                        <p class="text-xs text-slate-400 mt-1">Lease agreements, title deeds, etc. Max 10MB each.</p>
                    </x-forms.group>
                </div>

                <div class="md:col-span-6">
                    <x-forms.input name="video_url" label="Video Tour URL" type="url" :value="old('video_url')" placeholder="https://youtube.com/watch?v=..." />
                </div>

                <div class="md:col-span-6 flex items-end">
                    <x-forms.checkbox name="featured" :checked="old('featured')">Mark as Featured Property</x-forms.checkbox>
                </div>

                <div class="md:col-span-12">
                    <x-forms.textarea name="nearby_places" label="Nearby Places (JSON)" :value="old('nearby_places')" rows="3" class-input="font-mono text-xs" placeholder='[{"name":"Supermarket","distance":"200m"},{"name":"School","distance":"500m"}]' />
                </div>

                <div class="md:col-span-12 border-t border-slate-200 dark:border-slate-700 pt-4 flex items-center gap-3">
                    <x-forms.button variant="primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Property
                    </x-forms.button>
                    <a href="{{ route('properties.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('imageInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        for (const file of e.target.files) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const div = document.createElement('div');
                div.className = 'aspect-square rounded-lg overflow-hidden border border-slate-200';
                div.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
