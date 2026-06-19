@extends('layouts.app')
@section('title', __('Edit Tenant'))
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Edit Tenant') }}</h2>
    <a href="{{ route('tenants.index') }}" class="btn-secondary btn-sm">{{ __('Back') }}</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Tenant Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('tenants.update', $tenant) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <x-forms.select name="user_id" label="{{ __('User') }}" :options="\App\Models\User::where('role','tenant')->get()->mapWithKeys(fn($u) => [$u->id => $u->name])->toArray()" :value="old('user_id', $tenant->user_id)" />
                </div>
                <div class="md:col-span-6">
                    <x-forms.select name="unit_id" label="{{ __('Unit') }}" :options="$units->mapWithKeys(fn($u) => [$u->id => $u->unit_number.' - '.$u->property->name])->toArray()" :value="old('unit_id', $tenant->unit_id)" />
                </div>
                <div class="md:col-span-4"><x-forms.input name="emergency_contact_name" label="Emergency Contact" :value="old('emergency_contact_name', $tenant->emergency_contact_name)" /></div>
                <div class="md:col-span-4"><x-forms.input name="emergency_contact_phone" label="Emergency Phone" :value="old('emergency_contact_phone', $tenant->emergency_contact_phone)" /></div>
                <div class="md:col-span-4">
                    <x-forms.select name="status" label="{{ __('Status') }}" :options="['pending' => __('Pending'), 'active' => __('Active'), 'past' => 'Past']" :value="old('status', $tenant->status)" />
                </div>
                <div class="col-span-12"><x-forms.textarea name="notes" label="Notes" :value="old('notes', $tenant->notes)" rows="2" /></div>
                <div class="col-span-12"><x-forms.button variant="primary">Update Tenant</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection