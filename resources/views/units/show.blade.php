@extends('layouts.app')
@section('title', 'Unit '.$unit->unit_number)
@section('content')
@section('breadcrumbs')
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Units'), 'url' => route('units.index')],
        ['label' => 'Unit '.$unit->unit_number],
    ]" />
@endsection
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Unit {{ $unit->unit_number }}</h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('units.edit', $unit) }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors font-medium text-sm inline-flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit</a>
        <a href="{{ route('units.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Unit Details</h5></div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-sm text-slate-600"><strong>Property:</strong> {{ $unit->property->name }}</div>
                    <div class="text-sm text-slate-600"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $unit->type)) }}</div>
                    <div class="text-sm text-slate-600"><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $unit->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">{{ ucfirst($unit->status) }}</span></div>
                    <div class="text-sm text-slate-600"><strong>Bedrooms:</strong> {{ $unit->bedrooms }}</div>
                    <div class="text-sm text-slate-600"><strong>Bathrooms:</strong> {{ $unit->bathrooms }}</div>
                    <div class="text-sm text-slate-600"><strong>Rent:</strong> ${{ number_format($unit->rent_amount, 2) }}</div>
                    <div class="text-sm text-slate-600"><strong>Deposit:</strong> ${{ number_format($unit->security_deposit ?? 0, 2) }}</div>
                    <div class="md:col-span-2 text-sm text-slate-600"><strong>Description:</strong><br>{{ $unit->description ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        @if($unit->activeTenant)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Current Tenant</h5></div>
            <div class="p-6">
                <p class="text-sm text-slate-600"><strong>Name:</strong> {{ $unit->activeTenant->user->name }}</p>
                <p class="text-sm text-slate-600"><strong>Email:</strong> {{ $unit->activeTenant->user->email }}</p>
                <p class="text-sm text-slate-600"><strong>Phone:</strong> {{ $unit->activeTenant->user->phone ?? 'N/A' }}</p>
                @if($unit->activeLease)
                <p class="text-sm text-slate-600"><strong>Lease:</strong> {{ $unit->activeLease->start_date->format('M d, Y') }} - {{ $unit->activeLease->end_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Maintenance Requests</h5></div>
            <div class="p-6">
                @if($unit->maintenanceRequests->count())
                <div class="divide-y divide-slate-100">
                    @foreach($unit->maintenanceRequests as $mr)
                    <div class="px-4 py-3 text-sm flex items-center justify-between">
                        <span class="text-slate-600">{{ $mr->title }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $mr->status === 'open' ? 'bg-red-100 text-red-700' : ($mr->status === 'in_progress' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ ucfirst(str_replace('_',' ',$mr->status)) }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-500">No maintenance requests.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="lg:col-span-1">
        @if($unit->images)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
            <div class="px-6 py-4 border-b border-slate-200"><h5 class="text-lg font-semibold text-slate-800">Images</h5></div>
            <div class="p-6">@foreach($unit->images as $img)<img src="{{ asset('storage/'.$img) }}" class="max-w-full h-auto rounded mb-2">@endforeach</div>
        </div>
        @endif
    </div>
</div>
@endsection
