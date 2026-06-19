@extends('layouts.app')
@section('title', __('Create Lease'))
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Create Lease') }}</h2>
    <a href="{{ route('leases.index') }}" class="btn-secondary btn-sm">{{ __('Back') }}</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Lease Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('leases.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <x-forms.select name="unit_id" label="{{ __('Unit') }}" required :options="$units->mapWithKeys(fn($u) => [$u->id => $u->unit_number.' - '.$u->property->name.' ($'.number_format($u->rent_amount,2).')'])->toArray()" placeholder="Select Unit" />
                </div>
                <div class="md:col-span-2">
                    <x-forms.select name="tenant_id" label="{{ __('Tenant') }}" required :options="$tenants->mapWithKeys(fn($t) => [$t->id => $t->user->name])->toArray()" placeholder="Select Tenant" />
                </div>
                <div><x-forms.input name="start_date" label="Start Date" type="date" required /></div>
                <div><x-forms.input name="end_date" label="End Date" type="date" required /></div>
                <div><x-forms.input name="rent_amount" label="Rent Amount" type="number" step="0.01" required /></div>
                <div><x-forms.input name="security_deposit" label="Security Deposit" type="number" step="0.01" /></div>
                <div>
                    <x-forms.select name="payment_frequency" label="Payment Frequency" :options="['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly']" :value="old('payment_frequency', 'monthly')" />
                </div>
                <div><x-forms.input name="due_day" label="Due Day of Month" type="number" :value="old('due_day', 1)" min="1" max="31" /></div>
                <div>
                    <x-forms.select name="status" label="{{ __('Status') }}" :options="['pending' => __('Pending'), 'active' => __('Active')]" :value="old('status', 'pending')" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.textarea name="terms" label="Terms & Conditions" :value="old('terms')" rows="4" />
                </div>
                <div class="md:col-span-4">
                    <x-forms.button variant="primary">{{ __('Create Lease') }}</x-forms.button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection