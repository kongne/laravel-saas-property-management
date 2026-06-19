@extends('layouts.app')
@section('title', 'Edit Lease')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Lease</h2>
    <a href="{{ route('leases.index') }}" class="btn-secondary btn-sm">Back</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Lease Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('leases.update', $lease) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <x-forms.select name="unit_id" label="Unit" :options="$units->mapWithKeys(fn($u) => [$u->id => $u->unit_number.' - '.$u->property->name])->toArray()" :value="old('unit_id', $lease->unit_id)" />
                </div>
                <div class="md:col-span-2">
                    <x-forms.select name="tenant_id" label="Tenant" :options="$tenants->mapWithKeys(fn($t) => [$t->id => $t->user->name])->toArray()" :value="old('tenant_id', $lease->tenant_id)" />
                </div>
                <div><x-forms.input name="start_date" label="Start Date" type="date" :value="old('start_date', $lease->start_date->format('Y-m-d'))" /></div>
                <div><x-forms.input name="end_date" label="End Date" type="date" :value="old('end_date', $lease->end_date->format('Y-m-d'))" /></div>
                <div><x-forms.input name="rent_amount" label="Rent Amount" type="number" step="0.01" :value="old('rent_amount', $lease->rent_amount)" /></div>
                <div><x-forms.input name="security_deposit" label="Security Deposit" type="number" step="0.01" :value="old('security_deposit', $lease->security_deposit)" /></div>
                <div>
                    <x-forms.select name="payment_frequency" label="Frequency" :options="['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly']" :value="old('payment_frequency', $lease->payment_frequency)" />
                </div>
                <div><x-forms.input name="due_day" label="Due Day" type="number" :value="old('due_day', $lease->due_day)" min="1" max="31" /></div>
                <div>
                    <x-forms.select name="status" label="Status" :options="['pending' => 'Pending', 'active' => 'Active', 'expired' => 'Expired', 'terminated' => 'Terminated']" :value="old('status', $lease->status)" />
                </div>
                <div class="md:col-span-4"><x-forms.textarea name="terms" label="Terms" :value="old('terms', $lease->terms)" rows="3" /></div>
                <div class="md:col-span-4"><x-forms.button variant="primary">Update Lease</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection