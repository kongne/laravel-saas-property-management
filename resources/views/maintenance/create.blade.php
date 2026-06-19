@extends('layouts.app')
@section('title', 'New Maintenance Request')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">New Maintenance Request</h2>
    <a href="{{ route('maintenance.index') }}" class="btn-secondary btn-sm">Back</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Request Details</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <x-forms.select name="unit_id" label="Unit" required :options="$units->mapWithKeys(fn($u) => [$u->id => $u->unit_number.' - '.$u->property->name])->toArray()" placeholder="Select Unit" />
                </div>
                <div>
                    <x-forms.select name="priority" label="Priority" :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'emergency' => 'Emergency']" :value="old('priority', 'medium')" />
                </div>
                <div>
                    <x-forms.select name="category" label="Category" :options="['general' => 'General', 'plumbing' => 'Plumbing', 'electrical' => 'Electrical', 'hvac' => 'HVAC', 'appliance' => 'Appliance', 'structural' => 'Structural', 'pest' => 'Pest Control']" :value="old('category', 'general')" />
                </div>
                <div class="md:col-span-4"><x-forms.input name="title" label="Title" required /></div>
                <div class="md:col-span-4"><x-forms.textarea name="description" label="Description" required rows="3" /></div>
                <div><x-forms.input name="assigned_to" label="Assigned To" /></div>
                <div><x-forms.input name="cost" label="Estimated Cost" type="number" step="0.01" /></div>
                <div class="md:col-span-4"><x-forms.button variant="primary">Create Request</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection