@extends('layouts.app')
@section('title', __('Subscription History'))
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Billing'), 'url' => route('billing.index')],
        ['label' => __('Subscription History')],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ __('Subscription History') }}</h1>
    <a href="{{ route('billing.index') }}" class="btn-secondary btn-sm">Back to Billing</a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Plan</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Period</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Start</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">End</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Canceled</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $sub->plan?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="badge {{
                            $sub->isActive() ? 'badge-success' :
                            ($sub->isTrial() ? 'badge-info' :
                            ($sub->isPastDue() ? 'badge-warning' :
                            ($sub->isCanceled() ? 'badge-neutral' :
                            'badge-danger')))
                        }}">{{ ucfirst($sub->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600 capitalize">{{ $sub->billing_period }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $sub->starts_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $sub->ends_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $sub->canceled_at?->format('M d, Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <x-empty-state message="{{ __('No subscription history.') }}" />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
