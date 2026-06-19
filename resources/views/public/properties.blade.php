@extends('layouts.app')

@section('title', 'Property Listings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Property Listings</h1>
            <p class="text-sm text-slate-500 mt-1">Find your perfect property in Cameroon</p>
        </div>
        <span class="text-sm text-slate-500">{{ $properties->total() }} properties found</span>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <aside class="lg:col-span-3 mb-6 lg:mb-0">
            <form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-5 sticky top-24">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, city, address..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Type') }}</label>
                    <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('All Types') }}</option>
                        @foreach($types as $val => $label)
                        <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('City') }}</label>
                    <select name="city" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        x-data
                        @change="const d = document.querySelector('[name=district]'); if(d) { d.disabled = true; d.innerHTML='<option>Loading...</option>'; fetch('/api/locations/districts?city='+encodeURIComponent($el.value)).then(r=>r.json()).then(data=>{ d.disabled = false; d.innerHTML='<option value=\"\">All Districts</option>'+data.map(x=>'<option value=\"'+x+'\" '+(x==='{{ request('district') }}' ? 'selected' : '')+'>'+x+'</option>').join(''); }).catch(()=>{ d.disabled = false; d.innerHTML='<option value=\"\">All Districts</option>'; }); }>
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('District') }}</label>
                    <select name="district" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('All Districts') }}</option>
                        @if(request('city'))
                        @foreach(\App\Models\Property::districtsForCity(request('city')) as $district)
                        <option value="{{ $district }}" {{ request('district') === $district ? 'selected' : '' }}>{{ $district }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Price Range (XAF)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Features') }}</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Featured Only</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Amenities') }}</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($amenityOptions as $val => $label)
                        @php $checked = in_array($val, (array) request('amenities', [])); @endphp
                        <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="{{ $val }}" {{ $checked ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">Apply Filters</button>
                    <a href="{{ route('listings.index') }}" class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">{{ __('Reset') }}</a>
                </div>
            </form>
        </aside>

        <div class="lg:col-span-9">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-slate-500">{{ __('Showing') }} {{ $properties->firstItem() }}-{{ $properties->lastItem() }} {{ __('of') }} {{ $properties->total() }}</p>
                <select name="sort" form="filter-form" onchange="this.form.submit()" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Newest First</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                </select>
            </div>

            @if($properties->count())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($properties as $property)
                <a href="{{ route('listings.show', $property) }}" class="group bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-all">
                    <div class="aspect-video bg-slate-100 relative overflow-hidden">
                        @if($property->primary_image)
                        <img src="{{ asset('storage/'.$property->primary_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                        @if($property->featured)
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-400 text-amber-900 shadow">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ __('Featured') }}
                            </span>
                        </div>
                        @endif
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/90 text-slate-700 shadow-sm">{{ ucfirst($property->type) }}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $property->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $property->city }}{{ $property->district ? ' / '.$property->district : '' }}
                        </p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $property->units_count ?? $property->units->count() }} units
                                </span>
                            </div>
                            <span class="text-xs font-semibold text-indigo-600">{{ __('From') }} {{ number_format($property->units->min('rent_amount') ?? 0, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">
                <x-pagination :paginator="$properties" />
            </div>
            @else
            <div class="py-12">
                <x-empty-state type="search" title="No properties found" description="Try adjusting your filter criteria.">
                    <a href="{{ route('listings.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">Clear Filters</a>
                </x-empty-state>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
