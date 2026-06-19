@extends('layouts.app')
@section('title', $maintenanceRequest->title)
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ $maintenanceRequest->title }}</h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('maintenance.edit', $maintenanceRequest) }}" class="bg-amber-400 text-white px-4 py-2 rounded-lg hover:bg-amber-500 transition-colors font-medium text-sm inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <x-confirm action="{{ route('maintenance.destroy', $maintenanceRequest) }}" method="DELETE" message="Delete this maintenance request?" confirmText="Delete">
            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium text-sm inline-flex items-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </x-confirm>
        <a href="{{ route('maintenance.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">Request Details</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Unit:</strong> {{ $maintenanceRequest->unit->unit_number }} ({{ $maintenanceRequest->unit->property->name }})</div>
                    <div><strong>Priority:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $maintenanceRequest->priority === 'emergency' ? 'bg-red-100 text-red-700' : ($maintenanceRequest->priority === 'high' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ ucfirst($maintenanceRequest->priority) }}</span></div>
                    <div><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $maintenanceRequest->status === 'open' ? 'bg-red-100 text-red-700' : ($maintenanceRequest->status === 'in_progress' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ ucfirst(str_replace('_',' ',$maintenanceRequest->status)) }}</span></div>
                    <div><strong>Category:</strong> {{ ucfirst($maintenanceRequest->category) }}</div>
                    <div><strong>Requested:</strong> {{ $maintenanceRequest->requested_date->format('M d, Y') }}</div>
                    <div><strong>Resolved:</strong> {{ $maintenanceRequest->resolved_date ? $maintenanceRequest->resolved_date->format('M d, Y') : 'N/A' }}</div>
                    <div><strong>Assigned To:</strong> {{ $maintenanceRequest->assigned_to ?? 'Unassigned' }}</div>
                    <div><strong>Cost:</strong> ${{ number_format($maintenanceRequest->cost ?? 0, 2) }}</div>
                    <div class="md:col-span-2"><strong>Description:</strong><br>{{ $maintenanceRequest->description }}</div>
                    @if($maintenanceRequest->resolution_notes)
                    <div class="md:col-span-2 mt-3"><strong>Resolution Notes:</strong><br>{{ $maintenanceRequest->resolution_notes }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-6">
        @if($maintenanceRequest->isOpen())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">Assign</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('maintenance.assign', $maintenanceRequest) }}" method="POST" class="space-y-3">
                    @csrf
                    <div><input type="text" name="assigned_to" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Technician name" value="{{ $maintenanceRequest->assigned_to }}"></div>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm w-full">Assign</button>
                </form>
            </div>
        </div>
        @endif
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">Resolve</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('maintenance.resolve', $maintenanceRequest) }}" method="POST" class="space-y-3">
                    @csrf
                    <div><textarea name="resolution_notes" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="2" placeholder="Resolution notes"></textarea></div>
                    <div><input type="number" name="cost" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Cost" value="{{ $maintenanceRequest->cost }}" step="0.01"></div>
                    <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-medium text-sm w-full">Mark Resolved</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
