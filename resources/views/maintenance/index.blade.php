@extends('layouts.app')
@section('title', __('Maintenance Requests'))
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Maintenance Requests')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Maintenance Requests') }}</h2>
    <a href="{{ route('maintenance.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('New Request') }}
    </a>
</div>

<x-table :title="__('Maintenance Requests')" :headers="['title' => __('Title'), 'unit' => __('Unit'), 'priority' => __('Priority'), 'status' => __('Status'), 'date' => __('Date'), 'actions' => __('Actions')]">
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <select name="status" class="select !w-32 !py-1.5 !text-xs">
                <option value="">{{ __('All Status') }}</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>{{ __('Open') }}</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
            </select>
            <select name="priority" class="select !w-32 !py-1.5 !text-xs">
                <option value="">{{ __('All Priority') }}</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                <option value="emergency" {{ request('priority') === 'emergency' ? 'selected' : '' }}>{{ __('Emergency') }}</option>
            </select>
            <button type="submit" class="btn-secondary btn-sm">{{ __('Filter') }}</button>
        </form>
        <a href="{{ route('maintenance.export', request()->query()) }}" class="btn-secondary btn-sm" title="Export CSV">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('Export') }}
        </a>
    </x-slot>
    @forelse($requests as $r)
    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><a href="{{ route('maintenance.show', $r) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 font-medium">{{ $r->title }}</a></td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $r->unit->unit_number }} ({{ $r->unit->property->name }})</td>
        <td class="px-4 py-3">
            <span class="badge {{
                $r->priority === 'emergency' ? 'badge-danger' :
                ($r->priority === 'high' ? 'badge-warning' :
                'badge-success')
            }}">{{ ucfirst($r->priority) }}</span>
        </td>
        <td class="px-4 py-3">
            <span class="badge {{
                $r->status === 'open' ? 'badge-danger' :
                ($r->status === 'in_progress' ? 'badge-warning' :
                'badge-success')
            }}">{{ ucfirst(str_replace('_',' ',$r->status)) }}</span>
        </td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $r->requested_date->format('M d, Y') }}</td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-1">
                <a href="{{ route('maintenance.show', $r) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 dark:text-sky-400 rounded-md hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('maintenance.edit', $r) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 dark:text-amber-400 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-4 py-12 text-center">
            <x-empty-state :message="__('No maintenance requests found.')" />
        </td>
    </tr>
    @endforelse
    <x-slot name="footer">
        <x-pagination :paginator="$requests" />
    </x-slot>
</x-table>
@endsection