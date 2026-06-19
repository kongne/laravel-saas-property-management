@extends('layouts.app')
@section('title', __('Manage Plans'))
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Billing'), 'url' => route('billing.index')],
        ['label' => __('Manage Plans')],
    ]" />
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ __('Manage Plans') }}</h1>
    <a href="{{ route('billing.index') }}" class="btn-secondary btn-sm">{{ __('Back to Billing') }}</a>
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

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Plan') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Monthly') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Yearly') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Limits') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($plans as $plan)
                <tbody x-data="{ editing: false }" class="divide-y divide-slate-100 dark:divide-slate-700">
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" x-show="!editing">
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-800">{{ $plan->name }}</div>
                        <div class="text-xs text-slate-400">/{{ $plan->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">${{ number_format($plan->monthly_price, 0) }}</td>
                    <td class="px-4 py-3 text-slate-600">${{ number_format($plan->yearly_price, 0) }}</td>
                    <td class="px-4 py-3 text-xs text-slate-600">
                        <div>{{ __('Props') }}: {{ $plan->hasUnlimitedProperties() ? '∞' : $plan->max_properties }}</div>
                        <div>{{ __('Units') }}: {{ $plan->hasUnlimitedUnits() ? '∞' : $plan->max_units }}</div>
                        <div>{{ __('Tenants') }}: {{ $plan->hasUnlimitedTenants() ? '∞' : $plan->max_tenants }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $plan->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $plan->is_active ? __('Active') : __('Hidden') }}</span>
                        @if($plan->is_popular)
                        <span class="badge badge-info ml-1">{{ __('Popular') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <button @@click="editing = true" class="btn-secondary btn-sm text-xs">{{ __('Edit') }}</button>
                    </td>
                </tr>
                <tr x-show="editing" x-cloak>
                    <td colspan="6" class="px-4 py-4 bg-slate-50 dark:bg-slate-800/30">
                        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @csrf @method('PUT')
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Name') }}</label>
                                <input type="text" name="name" value="{{ $plan->name }}" class="input !w-full !py-1.5 !text-xs" required>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Slug') }}</label>
                                <input type="text" name="slug" value="{{ $plan->slug }}" class="input !w-full !py-1.5 !text-xs" required>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Monthly Price') }} (USD)</label>
                                <input type="number" name="monthly_price" value="{{ $plan->monthly_price }}" class="input !w-full !py-1.5 !text-xs" step="0.01" min="0" required>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Yearly Price') }} (USD)</label>
                                <input type="number" name="yearly_price" value="{{ $plan->yearly_price }}" class="input !w-full !py-1.5 !text-xs" step="0.01" min="0" required>
                            </div>
                            @foreach($currencies as $currency)
                            @if($currency->code !== 'USD')
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Monthly Price') }} ({{ $currency->code }})</label>
                                <input type="number" name="prices[{{ $currency->code }}][monthly]" value="{{ $plan->prices[$currency->code]['monthly'] ?? '' }}" class="input !w-full !py-1.5 !text-xs" step="1" min="0">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Yearly Price') }} ({{ $currency->code }})</label>
                                <input type="number" name="prices[{{ $currency->code }}][yearly]" value="{{ $plan->prices[$currency->code]['yearly'] ?? '' }}" class="input !w-full !py-1.5 !text-xs" step="1" min="0">
                            </div>
                            @endif
                            @endforeach
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Properties') }}</label>
                                <input type="number" name="max_properties" value="{{ $plan->max_properties }}" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Units') }}</label>
                                <input type="number" name="max_units" value="{{ $plan->max_units }}" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Tenants') }}</label>
                                <input type="number" name="max_tenants" value="{{ $plan->max_tenants }}" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Users') }}</label>
                                <input type="number" name="max_users" value="{{ $plan->max_users }}" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Features') }}</label>
                                <div class="flex flex-wrap gap-2">
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="can_export" value="0"><input type="checkbox" name="can_export" value="1" {{ $plan->can_export ? 'checked' : '' }} class="rounded"> {{ __('Export') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="can_access_audit" value="0"><input type="checkbox" name="can_access_audit" value="1" {{ $plan->can_access_audit ? 'checked' : '' }} class="rounded"> {{ __('Audit') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="has_advanced_reports" value="0"><input type="checkbox" name="has_advanced_reports" value="1" {{ $plan->has_advanced_reports ? 'checked' : '' }} class="rounded"> {{ __('Reports') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="has_api_access" value="0"><input type="checkbox" name="has_api_access" value="1" {{ $plan->has_api_access ? 'checked' : '' }} class="rounded"> {{ __('API') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="has_priority_support" value="0"><input type="checkbox" name="has_priority_support" value="1" {{ $plan->has_priority_support ? 'checked' : '' }} class="rounded"> {{ __('Support') }}</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Flags') }}</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="is_popular" value="0"><input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }} class="rounded"> {{ __('Popular') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} class="rounded"> {{ __('Active') }}</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Trial Days') }}</label>
                                <input type="number" name="trial_days" value="{{ $plan->trial_days }}" class="input !w-full !py-1.5 !text-xs" min="0">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Sort Order') }}</label>
                                <input type="number" name="sort_order" value="{{ $plan->sort_order }}" class="input !w-full !py-1.5 !text-xs" min="0">
                            </div>
                            <div class="md:col-span-3 lg:col-span-4 flex items-center gap-2 pt-2 border-t border-slate-200">
                                <button type="submit" class="btn-primary btn-sm">{{ __('Save Changes') }}</button>
                                <button type="button" @@click="editing = false" class="btn-secondary btn-sm">{{ __('Cancel') }}</button>
                            </div>
                        </form>
                        @if(!$plan->subscriptions()->exists())
                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="mt-3" onsubmit="return confirm('{{ __('Delete this plan?') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">{{ __('Delete') }}</button>
                        </form>
                        @endif
                    </td>
                </tr>
                </tbody>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">{{ __('Create New Plan') }}</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.plans.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @csrf
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Name') }}</label>
                <input type="text" name="name" class="input !w-full !py-1.5 !text-xs" required placeholder="{{ __('e.g. Premium') }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Slug') }}</label>
                <input type="text" name="slug" class="input !w-full !py-1.5 !text-xs" required placeholder="{{ __('premium') }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Monthly Price') }} (USD)</label>
                <input type="number" name="monthly_price" class="input !w-full !py-1.5 !text-xs" step="0.01" min="0" required value="0">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Yearly Price') }} (USD)</label>
                <input type="number" name="yearly_price" class="input !w-full !py-1.5 !text-xs" step="0.01" min="0" required value="0">
            </div>
            @foreach($currencies as $currency)
            @if($currency->code !== 'USD')
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Monthly Price') }} ({{ $currency->code }})</label>
                <input type="number" name="prices[{{ $currency->code }}][monthly]" class="input !w-full !py-1.5 !text-xs" step="1" min="0" value="0">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Yearly Price') }} ({{ $currency->code }})</label>
                <input type="number" name="prices[{{ $currency->code }}][yearly]" class="input !w-full !py-1.5 !text-xs" step="1" min="0" value="0">
            </div>
            @endif
            @endforeach
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Properties') }}</label>
                <input type="number" name="max_properties" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Units') }}</label>
                <input type="number" name="max_units" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Tenants') }}</label>
                <input type="number" name="max_tenants" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Max Users') }}</label>
                <input type="number" name="max_users" class="input !w-full !py-1.5 !text-xs" placeholder="{{ __('Unlimited') }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Trial Days') }}</label>
                <input type="number" name="trial_days" class="input !w-full !py-1.5 !text-xs" min="0" value="0">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">{{ __('Sort Order') }}</label>
                <input type="number" name="sort_order" class="input !w-full !py-1.5 !text-xs" min="0" value="0">
            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Features') }}</label>
                                <div class="flex flex-wrap gap-2">
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="can_export" value="0"><input type="checkbox" name="can_export" value="1" class="rounded"> {{ __('Export') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="can_access_audit" value="0"><input type="checkbox" name="can_access_audit" value="1" class="rounded"> {{ __('Audit') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="has_advanced_reports" value="0"><input type="checkbox" name="has_advanced_reports" value="1" class="rounded"> {{ __('Reports') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="has_api_access" value="0"><input type="checkbox" name="has_api_access" value="1" class="rounded"> {{ __('API') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="has_priority_support" value="0"><input type="checkbox" name="has_priority_support" value="1" class="rounded"> {{ __('Support') }}</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Flags') }}</label>
                                <div class="flex gap-3">
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="is_popular" value="0"><input type="checkbox" name="is_popular" value="1" class="rounded"> {{ __('Popular') }}</label>
                                    <label class="flex items-center gap-1 text-xs"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" class="rounded" checked> {{ __('Active') }}</label>
                                </div>
                            </div>
            <div class="md:col-span-3 lg:col-span-4 flex items-center gap-2 pt-2 border-t border-slate-200">
                <button type="submit" class="btn-primary btn-sm">{{ __('Create Plan') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
