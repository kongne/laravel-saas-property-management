@extends('layouts.app')

@section('title', $property->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ $property->name }}</h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('properties.edit', $property) }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors font-medium text-sm inline-flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit</a>
        <a href="{{ route('properties.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Property Details</h5></div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-sm text-slate-600"><strong>Type:</strong> {{ ucfirst($property->type) }}</div>
                    <div class="text-sm text-slate-600"><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $property->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ $property->status }}</span></div>
                    <div class="text-sm text-slate-600"><strong>Address:</strong> {{ $property->address }}</div>
                    <div class="text-sm text-slate-600"><strong>City:</strong> {{ $property->city }}, {{ $property->state }} {{ $property->zip_code }}</div>
                    <div class="text-sm text-slate-600"><strong>Country:</strong> {{ $property->country }}</div>
                    <div class="text-sm text-slate-600"><strong>Total Units:</strong> {{ $property->total_units }}</div>
                    <div class="text-sm text-slate-600"><strong>Area:</strong> {{ $property->area_sqft ? number_format($property->area_sqft, 2).' sqft' : 'N/A' }}</div>
                    <div class="md:col-span-2 text-sm text-slate-600"><strong>Description:</strong><br>{{ $property->description ?? 'No description' }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-slate-800">Units ({{ $property->units->count() }})</h5>
                <a href="{{ route('units.create', ['property_id' => $property->id]) }}" class="bg-indigo-600 text-white px-2.5 py-1.5 text-xs rounded-md hover:bg-indigo-700 transition-colors font-medium inline-flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Unit</a>
            </div>
            <div class="p-6">
                @if($property->units->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr><th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Unit #</th><th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Type</th><th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Bed/Bath</th><th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Rent</th><th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Status</th><th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Tenant</th></tr></thead>
                        <tbody>
                            @foreach($property->units as $unit)
                            <tr>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600"><a href="{{ route('units.show', $unit) }}" class="text-indigo-600 hover:text-indigo-800">{{ $unit->unit_number }}</a></td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($unit->rent_amount, 2) }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600"><span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $unit->status === 'available' ? 'bg-emerald-100 text-emerald-700' : ($unit->status === 'occupied' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($unit->status) }}</span></td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $unit->activeTenant->user->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-slate-500">No units added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        @if($property->images)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Images</h5></div>
            <div class="p-6">
                @foreach($property->images as $image)
                    <img src="{{ asset('storage/'.$image) }}" class="max-w-full h-auto rounded mb-2">
                @endforeach
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Quick Stats</h5></div>
            <div class="p-6">
                <p class="text-sm text-slate-600"><strong>Available:</strong> {{ $property->availableUnits->count() }}</p>
                <p class="text-sm text-slate-600"><strong>Occupied:</strong> {{ $property->occupiedUnits->count() }}</p>
                <p class="text-sm text-slate-600"><strong>Active Leases:</strong> {{ $property->activeLeases->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
