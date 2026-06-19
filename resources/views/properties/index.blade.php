@extends('layouts.app')

@section('title', __('Properties'))

@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Properties')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Properties') }}</h2>
    <a href="{{ route('properties.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Property') }}
    </a>
</div>

<x-table title="{{ __('Properties') }}" :headers="['name' => __('Name'), 'type' => __('Type'), 'city' => __('City'), 'units' => __('Units'), 'status' => __('Status'), 'actions' => __('Actions')]">
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="text" name="search" class="input !w-48 !py-1.5 !text-xs" placeholder="{{ __('Search...') }}" value="{{ request('search') }}">
            <select name="status" class="select !w-32 !py-1.5 !text-xs">
                <option value="">{{ __('All Status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>{{ __('Under Maintenance') }}</option>
            </select>
            <button type="submit" class="btn-secondary btn-sm">{{ __('Filter') }}</button>
        </form>
        <a href="{{ route('properties.export', request()->query()) }}" class="btn-secondary btn-sm" title="Export CSV">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('Export') }}
        </a>
    </x-slot>
    @forelse($properties as $property)
    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><a href="{{ route('properties.show', $property) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 font-medium">{{ $property->name }}</a></td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ ucfirst($property->type) }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $property->city }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $property->units_count ?? $property->units->count() }}</td>
        <td class="px-4 py-3">
            <span class="badge {{
                $property->status === 'active' ? 'badge-success' :
                ($property->status === 'inactive' ? 'badge-neutral' :
                'badge-warning')
            }}">
                {{ ucfirst(str_replace('_', ' ', $property->status)) }}
            </span>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-1">
                <a href="{{ route('properties.show', $property) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 dark:text-sky-400 rounded-md hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 dark:text-amber-400 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <x-confirm action="{{ route('properties.destroy', $property) }}" method="DELETE" message="Delete this property?">
                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </x-confirm>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-4 py-12 text-center">
            <x-empty-state :message="__('No properties found.')" />
        </td>
    </tr>
    @endforelse
    <x-slot name="footer">
        <x-pagination :paginator="$properties" />
    </x-slot>
</x-table>
@endsection
