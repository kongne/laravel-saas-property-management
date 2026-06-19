@extends('layouts.app')

@section('title', 'Units')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Units</h2>
    <a href="{{ route('units.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Unit
    </a>
</div>

<x-table title="Units" :headers="['unit' => 'Unit #', 'property' => 'Property', 'type' => 'Type', 'bed_bath' => 'Bed/Bath', 'rent' => 'Rent', 'status' => 'Status', 'actions' => 'Actions']">
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <select name="property_id" class="select !w-40 !py-1.5 !text-xs">
                <option value="">All Properties</option>
                @foreach($properties as $p)
                    <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="status" class="select !w-32 !py-1.5 !text-xs">
                <option value="">All Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
            </select>
            <input type="text" name="search" class="input !w-36 !py-1.5 !text-xs" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit" class="btn-secondary btn-sm">Filter</button>
        </form>
    </x-slot>
    @forelse($units as $unit)
    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><a href="{{ route('units.show', $unit) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 font-medium">{{ $unit->unit_number }}</a></td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $unit->property->name }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $unit->bedrooms }}/{{ $unit->bathrooms }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">${{ number_format($unit->rent_amount, 2) }}</td>
        <td class="px-4 py-3">
            <span class="badge {{
                $unit->status === 'available' ? 'badge-success' :
                ($unit->status === 'occupied' ? 'badge-info' :
                'badge-warning')
            }}">{{ ucfirst($unit->status) }}</span>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-1">
                <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 dark:text-sky-400 rounded-md hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 dark:text-amber-400 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <x-confirm action="{{ route('units.destroy', $unit) }}" method="DELETE" message="Delete this unit?">
                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </x-confirm>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="px-4 py-12 text-center">
            <x-empty-state message="No units found." />
        </td>
    </tr>
    @endforelse
    <x-slot name="footer">
        <x-pagination :paginator="$units" />
    </x-slot>
</x-table>
@endsection