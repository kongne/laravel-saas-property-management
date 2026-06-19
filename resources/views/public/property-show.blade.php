@extends('layouts.app')

@section('title', $property->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('listings.index') }}" class="hover:text-indigo-600">Listings</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 font-medium">{{ $property->name }}</span>
    </nav>

    @if($property->images)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-8 rounded-xl overflow-hidden" x-data="{ activeImage: 0 }">
        <div class="md:col-span-4 relative bg-slate-900">
            <img src="{{ asset('storage/'.$property->images[0]) }}" class="w-full h-72 md:h-96 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            @if($property->featured)
            <div class="absolute top-4 left-4">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-amber-400 text-amber-900 shadow-lg">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Featured
                </span>
            </div>
            @endif
        </div>
        @if(count($property->images) > 1)
        <div class="md:col-span-4 grid grid-cols-4 gap-2">
            @foreach(array_slice($property->images, 1) as $img)
            <div class="aspect-video rounded-lg overflow-hidden">
                <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity">
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">{{ $property->name }}</h1>
                <p class="text-slate-500 mt-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $property->address }}, {{ $property->city }}{{ $property->district ? ' / '.$property->district : '' }}, {{ $property->country ?? 'Cameroon' }}
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4 p-4 bg-slate-50 rounded-xl">
                <div class="text-center">
                    <p class="text-2xl font-bold text-slate-800">{{ $property->total_units ?? 0 }}</p>
                    <p class="text-xs text-slate-500">Total Units</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ $property->units->count() }}</p>
                    <p class="text-xs text-slate-500">Available</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $property->area_sqft ? number_format($property->area_sqft) : 'N/A' }}</p>
                    <p class="text-xs text-slate-500">Area (sqft)</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Description</h2>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $property->description ?? 'No description provided.' }}</p>
            </div>

            @if($property->amenities)
            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Amenities</h2>
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
            @endif

            @if($property->video_url)
            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Video Tour</h2>
                <div class="aspect-video rounded-xl overflow-hidden">
                    <iframe src="{{ str_replace('watch?v=', 'embed/', $property->video_url) }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            @endif

            @if($property->nearby_places)
            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Nearby Places</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($property->nearby_places as $place)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="text-sm text-slate-700">{{ $place['name'] ?? '' }}</span>
                        <span class="text-xs text-slate-400">{{ $place['distance'] ?? '' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($property->latitude && $property->longitude)
            <div>
                <h2 class="text-lg font-semibold text-slate-800 mb-3">Location</h2>
                <div class="h-64 rounded-xl overflow-hidden">
                    <iframe width="100%" height="100%" frameborder="0" style="border:0" src="https://www.openstreetmap.org/export/embed.html?bbox={{ $property->longitude - 0.01 }}%2C{{ $property->latitude - 0.01 }}%2C{{ $property->longitude + 0.01 }}%2C{{ $property->latitude + 0.01 }}&layer=mapnik&marker={{ $property->latitude }}%2C{{ $property->longitude }}" allowfullscreen></iframe>
                </div>
            </div>
            @endif
        </div>

        <div class="lg:col-span-1 space-y-4 mt-6 lg:mt-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 sticky top-24">
                <p class="text-sm text-slate-500 mb-1">Starting from</p>
                <p class="text-3xl font-bold text-indigo-600">{{ number_format($property->units->min('rent_amount') ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal text-slate-500">FCFA/mo</span></p>

                <hr class="my-4">

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Property Type</span>
                        <span class="font-medium text-slate-800">{{ ucfirst($property->type) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">{{ ucfirst($property->status) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Available Units</span>
                        <span class="font-medium text-slate-800">{{ $property->units->count() }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <a href="{{ route('register') }}" class="w-full bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Contact Agent
                </a>
                <p class="text-xs text-slate-400 text-center mt-2">Sign up to inquire about this property</p>
            </div>
        </div>
    </div>
</div>
@endsection
