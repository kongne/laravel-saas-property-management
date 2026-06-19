@extends('layouts.app')

@section('title', 'Units')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Units</h2>
    <a href="{{ route('units.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Unit
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
            <div class="md:col-span-3">
                <select name="property_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Properties</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>
            <div class="md:col-span-4">
                <input type="text" name="search" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search unit number or type..." value="{{ request('search') }}">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="w-full px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">Filter</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Unit #</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Property</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Bed/Bath</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Rent</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600"><a href="{{ route('units.show', $unit) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ $unit->unit_number }}</a></td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $unit->property->name }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($unit->rent_amount, 2) }}</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{
                                $unit->status === 'available' ? 'bg-emerald-100 text-emerald-700' :
                                ($unit->status === 'occupied' ? 'bg-indigo-100 text-indigo-700' :
                                'bg-amber-100 text-amber-700')
                            }}">{{ ucfirst($unit->status) }}</span>
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 rounded-md hover:bg-sky-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 rounded-md hover:bg-amber-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center justify-center w-8 h-8 text-red-600 rounded-md hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-slate-500 border-t border-slate-100">No units found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection
