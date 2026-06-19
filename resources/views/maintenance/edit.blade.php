@extends('layouts.app')
@section('title', __('Edit Maintenance Request'))
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Edit Request') }}</h2>
    <a href="{{ route('maintenance.index') }}" class="btn-secondary btn-sm">{{ __('Back') }}</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">{{ __('Request Details') }}</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('maintenance.update', $maintenanceRequest) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <x-forms.select name="unit_id" :label="__('Unit')" :options="$units->mapWithKeys(fn($u) => [$u->id => $u->unit_number.' - '.$u->property->name])->toArray()" :value="old('unit_id', $maintenanceRequest->unit_id)" />
                </div>
                <div>
                    <x-forms.select name="priority" :label="__('Priority')" :options="['low' => __('Low'), 'medium' => __('Medium'), 'high' => __('High'), 'emergency' => __('Emergency')]" :value="old('priority', $maintenanceRequest->priority)" />
                </div>
                <div>
                    <x-forms.select name="status" :label="__('Status')" :options="['open' => __('Open'), 'in_progress' => __('In Progress'), 'resolved' => __('Resolved'), 'closed' => __('Closed')]" :value="old('status', $maintenanceRequest->status)" />
                </div>
                <div class="md:col-span-4"><x-forms.input name="title" :label="__('Title')" :value="old('title', $maintenanceRequest->title)" /></div>
                <div class="md:col-span-4"><x-forms.textarea name="description" :label="__('Description')" :value="old('description', $maintenanceRequest->description)" rows="3" /></div>
                <div class="md:col-span-4"><x-forms.button variant="primary">{{ __('Update') }}</x-forms.button></div>
            </div>
        </form>
    </div>
</div>
@endsection