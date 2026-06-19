@extends('layouts.app')
@section('title', __('Audit Log'))
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Audit Log')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ __('Audit Log') }}</h2>
        <p class="text-sm text-slate-500 mt-1">{{ __('Track all user activity across the system') }}</p>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="card-header">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <select name="action" class="select !w-40 !py-1.5 !text-xs">
                <option value="">{{ __('All Actions') }}</option>
                @foreach($actions as $a)
                <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                @endforeach
            </select>
            <input type="text" name="search" class="input !w-48 !py-1.5 !text-xs" placeholder="{{ __('Search description...') }}" value="{{ request('search') }}">
            <button type="submit" class="btn-secondary btn-sm">{{ __('Filter') }}</button>
            <a href="{{ route('audit.index') }}" class="btn-secondary btn-sm">{{ __('Clear') }}</a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Timestamp') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('User') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Action') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Description') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('IP Address') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">{{ $log->user->name ?? __('System') }}</td>
                    <td class="px-4 py-3">
                        <span class="badge badge-neutral text-xs">{{ ucfirst($log->action) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate">{{ $log->description }}</td>
                    <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center">
                        <x-empty-state message="{{ __('No activity logged yet.') }}" />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection