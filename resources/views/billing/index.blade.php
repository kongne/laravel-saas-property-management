@extends('layouts.app')
@section('title', __('Billing & Plan'))
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Billing')],
    ]" />
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ __('Billing & Plan') }}</h1>
    <div class="flex items-center gap-2">
        <div class="flex items-center gap-1 text-xs text-slate-500 bg-slate-100 dark:bg-slate-700 rounded-lg p-0.5">
            @foreach($currencies as $c)
            <a href="{{ route('currency.switch', $c->code) }}" class="px-2 py-1 rounded-md transition-colors {{ $currentCurrency === $c->code ? 'bg-white dark:bg-slate-600 text-indigo-600 dark:text-indigo-400 font-semibold shadow-sm' : 'hover:text-slate-700 dark:hover:text-slate-300' }}">
                {{ $c->code }}
            </a>
            @endforeach
        </div>
        <a href="{{ route('billing.history') }}" class="btn-secondary btn-sm">{{ __('View History') }}</a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg text-sm text-emerald-700 dark:text-emerald-300">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">
    {{ session('error') }}
</div>
@endif

@if($currentSubscription)
<div class="card mb-6">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">{{ __('Current Plan') }}</h3>
    </div>
    <div class="p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-800">{{ $currentPlan?->name ?? 'No Plan' }}</h2>
                    @if($currentSubscription->isTrial())
                    <span class="badge badge-info">Trial</span>
                    @elseif($currentSubscription->isActive())
                    <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($currentSubscription->isCanceled())
                    <span class="badge badge-neutral">Canceled</span>
                    @elseif($currentSubscription->isExpired())
                    <span class="badge badge-danger">{{ __('Expired') }}</span>
                    @elseif($currentSubscription->isPastDue())
                    <span class="badge badge-warning">Past Due</span>
                    @endif
                </div>
                @if($currentPlan)
                <p class="text-sm text-slate-500 mt-1">{{ $currentPlan->description }}</p>
                @endif
                <div class="flex items-center gap-4 mt-3 text-sm text-slate-600">
                    @if($currentSubscription->onTrial())
                    <span>Trial ends in <strong>{{ $currentSubscription->daysRemaining() }} day(s)</strong></span>
                    @elseif($currentSubscription->ends_at)
                    <span>Renewal: <strong>{{ $currentSubscription->ends_at->format('M d, Y') }}</strong></span>
                    @endif
                    <span>Billing: <strong>{{ ucfirst($currentSubscription->billing_period) }}</strong></span>
                </div>
            </div>
            @if(!$currentSubscription->isCanceled() && !$currentSubscription->isExpired())
            <form action="{{ route('billing.cancel') }}" method="POST" onsubmit="return confirm('Cancel your subscription? You will lose access to paid features at the end of the billing period.')">
                @csrf
                <button type="submit" class="btn-danger btn-sm">{{ __('Cancel') }}</button>
            </form>
            @endif
        </div>
        @if($currentPlan)
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 pt-4 border-t border-slate-200">
            <div>
                <p class="text-xs text-slate-400">{{ __('Properties') }}</p>
                <p class="text-sm font-semibold text-slate-700">{{ $currentPlan->hasUnlimitedProperties() ? __('Unlimited') : $currentPlan->max_properties }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">{{ __('Units') }}</p>
                <p class="text-sm font-semibold text-slate-700">{{ $currentPlan->hasUnlimitedUnits() ? __('Unlimited') : $currentPlan->max_units }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">{{ __('Tenants') }}</p>
                <p class="text-sm font-semibold text-slate-700">{{ $currentPlan->hasUnlimitedTenants() ? __('Unlimited') : $currentPlan->max_tenants }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">{{ __('Users') }}</p>
                <p class="text-sm font-semibold text-slate-700">{{ $currentPlan->hasUnlimitedUsers() ? __('Unlimited') : $currentPlan->max_users }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 mt-3">
            @foreach([__('Export') => $currentPlan->can_export, __('Audit Log') => $currentPlan->can_access_audit, __('Advanced Reports') => $currentPlan->has_advanced_reports, __('API Access') => $currentPlan->has_api_access, __('Priority Support') => $currentPlan->has_priority_support] as $label => $enabled)
            <span class="inline-flex items-center gap-1 text-xs {{ $enabled ? 'text-emerald-600' : 'text-slate-400' }}">
                @if($enabled)
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                @else
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                @endif
                {{ $label }}
            </span>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif

@if(Auth::user()->isAdmin())
<div class="card mb-6">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Plan Management</h3>
        <a href="{{ route('admin.plans.index') }}" class="btn-secondary btn-sm">{{ __('Manage Plans') }}</a>
    </div>
</div>
@endif

@if(count($plans))
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">{{ __('Available Plans') }}</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($plans as $plan)
            <div class="relative border rounded-xl p-5 {{ $currentPlan?->id === $plan->id ? 'border-indigo-400 ring-2 ring-indigo-200' : 'border-slate-200' }} {{ $plan->is_popular ? 'border-indigo-300' : '' }}">
                @if($plan->is_popular)
                <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-3 py-0.5 bg-indigo-600 text-white text-[10px] font-semibold uppercase tracking-wider rounded-full">{{ __('Popular') }}</span>
                @endif
                <div class="text-center">
                    <h3 class="text-lg font-bold text-slate-800">{{ $plan->name }}</h3>
                    @php
                        $monthly = $plan->getPrice($currentCurrency, 'monthly');
                        $yearly = $plan->getPrice($currentCurrency, 'yearly');
                        $currencyModel = $currencies->firstWhere('code', $currentCurrency);
                        $symbol = $currencyModel?->symbol ?? '$';
                    @endphp
                    <div class="mt-2">
                        <span class="text-3xl font-extrabold text-slate-900">{{ $symbol }}{{ number_format($monthly) }}</span>
                        <span class="text-sm text-slate-500">/mo</span>
                    </div>
                    @if($yearly > 0)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $symbol }}{{ number_format($yearly) }}/year <span class="text-emerald-600 font-medium">(save {{ round((1 - $yearly / ($monthly * 12)) * 100) }}%)</span></p>
                    @else
                    <p class="text-xs text-slate-400 mt-0.5">&nbsp;</p>
                    @endif
                    <p class="text-xs text-slate-500 mt-2">{{ $plan->description }}</p>
                </div>
                <ul class="mt-4 space-y-2 text-xs">
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full {{ $plan->hasUnlimitedProperties() || $plan->max_properties > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        {{ $plan->hasUnlimitedProperties() ? __('Unlimited') : $plan->max_properties }} {{ __('Properties') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full {{ $plan->hasUnlimitedUnits() || $plan->max_units > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        {{ $plan->hasUnlimitedUnits() ? __('Unlimited') : $plan->max_units }} {{ __('Units') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full {{ $plan->hasUnlimitedTenants() || $plan->max_tenants > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        {{ $plan->hasUnlimitedTenants() ? __('Unlimited') : $plan->max_tenants }} {{ __('Tenants') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full {{ $plan->can_export ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        {{ $plan->can_export ? __('Exports') : __('No Exports') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full {{ $plan->can_access_audit ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        {{ $plan->can_access_audit ? __('Audit Logs') : __('No Audit Logs') }}
                    </li>
                </ul>
                <div class="mt-5">
                    @if($currentPlan?->id === $plan->id)
                    <button disabled class="w-full btn-secondary btn-sm opacity-50 cursor-not-allowed">{{ __('Current Plan') }}</button>
                    @elseif($plan->monthly_price == 0 && !$currentPlan)
                    <form action="{{ route('billing.change-plan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="w-full btn-secondary btn-sm">Select Free</button>
                    </form>
                    @else
                    <form action="{{ route('billing.change-plan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="w-full {{ $plan->is_popular ? 'btn-primary' : 'btn-secondary' }} btn-sm">Choose {{ $plan->name }}</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
