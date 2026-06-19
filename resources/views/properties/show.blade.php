@extends('layouts.app')

@section('title', $property->name)

@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Properties'), 'url' => route('properties.index')],
        ['label' => $property->name],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ $property->name }}</h1>
    <div class="flex items-center gap-2">
        <a href="{{ route('properties.edit', $property) }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            {{ __('Edit') }}
        </a>
        <a href="{{ route('properties.index') }}" class="btn-secondary btn-sm">{{ __('Back') }}</a>
    </div>
</div>

<x-breadcrumbs :items="[['label' => __('Properties'), 'url' => route('properties.index')], ['label' => $property->name]]" />

{{-- Image Gallery --}}
@if($property->images)
<div class="card mb-6 overflow-hidden" x-data="{ activeImage: 0 }">
    <div class="relative bg-slate-900">
        <img src="{{ asset('storage/'.$property->images[0]) }}" class="w-full h-64 md:h-96 object-cover" :src="'{{ asset('storage/') }}/' + ($refs.gallery?.querySelectorAll('img')?.[activeImage]?.dataset?.src || '{{ $property->images[0] }}')">
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        @if($property->featured)
        <div class="absolute top-4 left-4">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-amber-400 text-amber-900 shadow-lg">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ __('Featured') }}
            </span>
        </div>
        @endif
        <div class="absolute bottom-4 right-4 flex gap-2" x-ref="gallery">
            @foreach($property->images as $i => $img)
            <button @@click="activeImage = {{ $i }}" class="w-12 h-12 rounded-lg overflow-hidden border-2 transition-colors" :class="activeImage === {{ $i }} ? 'border-white' : 'border-transparent opacity-60 hover:opacity-100'">
                <img src="{{ asset('storage/'.$img) }}" data-src="{{ $img }}" class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        {{-- Details --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Property Details</h3>
                <span class="badge {{ $property->status === 'active' ? 'badge-success' : ($property->status === 'inactive' ? 'badge-neutral' : 'badge-warning') }}">{{ ucfirst(str_replace('_', ' ', $property->status)) }}</span>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-4">{{ $property->description ?? 'No description provided.' }}</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('Type') }}</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">{{ ucfirst($property->type) }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Area</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $property->area_sqft ? number_format($property->area_sqft).' sqft' : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('Total Units') }}</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $property->total_units ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('Address') }}</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $property->address }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">{{ __('City') }}</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $property->city }}{{ $property->district ? ' / '.$property->district : '' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Region/Country</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $property->state ?? 'N/A' }}, {{ $property->country ?? 'Cameroon' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amenities --}}
        @if($property->amenities)
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">{{ __('Amenities') }}</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($property->amenities as $amenity)
                    @php $options = \App\Models\Property::amenityOptions(); @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $options[$amenity] ?? ucfirst($amenity) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Video Tour --}}
        @if($property->video_url)
        <div class="card overflow-hidden">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Video Tour</h3>
            </div>
            <div class="aspect-video">
                <iframe src="{{ str_replace('watch?v=', 'embed/', $property->video_url) }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
        @endif

        {{-- Units --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">{{ __('Units') }} ({{ $property->units->count() }})</h3>
                <a href="{{ route('units.create', ['property_id' => $property->id]) }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('Add Unit') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                @if($property->units->count())
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Unit #') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Bed/Bath') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Rent') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Tenant') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($property->units as $unit)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3"><a href="{{ route('units.show', $unit) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">{{ $unit->unit_number }}</a></td>
                            <td class="px-4 py-3 text-slate-600">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
                            <td class="px-4 py-3 text-slate-600">${{ number_format($unit->rent_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $unit->status === 'available' ? 'badge-success' : ($unit->status === 'occupied' ? 'badge-info' : 'badge-warning') }}">{{ ucfirst($unit->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $unit->activeTenant->user->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-6 text-center text-slate-500">
                    <p class="text-sm">No units added yet.</p>
                    <a href="{{ route('units.create', ['property_id' => $property->id]) }}" class="btn-primary btn-sm mt-3 inline-flex">Add First Unit</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        {{-- Quick Stats --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Quick Stats</h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Available Units</span>
                    <span class="font-semibold text-emerald-600">{{ $property->availableUnits->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Occupied Units</span>
                    <span class="font-semibold text-indigo-600">{{ $property->occupiedUnits->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Active Leases</span>
                    <span class="font-semibold text-slate-800">{{ $property->activeLeases->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm border-t border-slate-100 pt-3">
                    <span class="text-slate-500">Feature Status</span>
                    <span class="badge {{ $property->featured ? 'badge-warning' : 'badge-neutral' }}">{{ $property->featured ? __('Featured') : 'Standard' }}</span>
                </div>
            </div>
        </div>

        {{-- Nearby Places --}}
        @if($property->nearby_places)
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Nearby Places</h3>
            </div>
            <div class="p-4 space-y-2">
                @foreach($property->nearby_places as $place)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-700">{{ $place['name'] ?? '' }}</span>
                    <span class="text-xs text-slate-400">{{ $place['distance'] ?? '' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Location Map --}}
        @if($property->latitude && $property->longitude)
        <div class="card overflow-hidden">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Location</h3>
            </div>
            <div class="h-48 bg-slate-200">
                <iframe width="100%" height="100%" frameborder="0" style="border:0" src="https://www.openstreetmap.org/export/embed.html?bbox={{ $property->longitude - 0.01 }}%2C{{ $property->latitude - 0.01 }}%2C{{ $property->longitude + 0.01 }}%2C{{ $property->latitude + 0.01 }}&layer=mapnik&marker={{ $property->latitude }}%2C{{ $property->longitude }}" allowfullscreen></iframe>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
