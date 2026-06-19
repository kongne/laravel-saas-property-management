@extends('layouts.app')
@section('title', 'Edit Maintenance Request')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Request</h2>
    <a href="{{ route('maintenance.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('maintenance.update', $maintenanceRequest) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit</label>
                    <select name="unit_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $maintenanceRequest->unit_id == $u->id ? 'selected' : '' }}>{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Priority</label>
                    <select name="priority" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['low','medium','high','emergency'] as $p)
                            <option value="{{ $p }}" {{ $maintenanceRequest->priority === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['open','in_progress','resolved','closed'] as $s)
                            <option value="{{ $s }}" {{ $maintenanceRequest->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Title</label><input type="text" name="title" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $maintenanceRequest->title }}"></div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label><textarea name="description" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="3">{{ $maintenanceRequest->description }}</textarea></div>
                <div class="md:col-span-4"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Update</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
