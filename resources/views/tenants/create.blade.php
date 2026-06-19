@extends('layouts.app')
@section('title', 'Add Tenant')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Add Tenant</h2>
    <a href="{{ route('tenants.index') }}" class="btn-secondary btn-sm">Back</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Tenant Information</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('tenants.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <x-forms.select name="user_id" label="User (Tenant Account)" required :options="\App\Models\User::where('role','tenant')->get()->mapWithKeys(fn($u) => [$u->id => $u->name.' ('.$u->email.')'])->toArray()" placeholder="Select User" />
                    <small class="text-xs text-slate-500 mt-1">Create a user account for the tenant first</small>
                </div>
                <div class="md:col-span-6">
                    <x-forms.select name="unit_id" label="Unit" required :options="$units->mapWithKeys(fn($u) => [$u->id => $u->unit_number.' - '.$u->property->name.' ($'.number_format($u->rent_amount,2).')'])->toArray()" placeholder="Select Unit" />
                </div>
                <div class="md:col-span-4"><x-forms.input name="emergency_contact_name" label="Emergency Contact" /></div>
                <div class="md:col-span-4"><x-forms.input name="emergency_contact_phone" label="Emergency Phone" /></div>
                <div class="md:col-span-4">
                    <x-forms.select name="status" label="Status" :options="['pending' => 'Pending', 'active' => 'Active']" :value="old('status', 'pending')" />
                </div>
                <div class="col-span-12"><x-forms.button variant="primary">Save Tenant</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection