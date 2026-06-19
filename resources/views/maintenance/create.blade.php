@extends('layouts.app')
@section('title', 'New Maintenance Request')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">New Maintenance Request</h2>
    <a href="{{ route('maintenance.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit *</label>
                    <select name="unit_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Priority</label>
                    <select name="priority" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Category</label>
                    <select name="category" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="general">General</option>
                        <option value="plumbing">Plumbing</option>
                        <option value="electrical">Electrical</option>
                        <option value="hvac">HVAC</option>
                        <option value="appliance">Appliance</option>
                        <option value="structural">Structural</option>
                        <option value="pest">Pest Control</option>
                    </select>
                </div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Title *</label><input type="text" name="title" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('title') }}" required></div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Description *</label><textarea name="description" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="3" required>{{ old('description') }}</textarea></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Assigned To</label><input type="text" name="assigned_to" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('assigned_to') }}"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Estimated Cost</label><input type="number" name="cost" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('cost') }}" step="0.01"></div>
                <div class="md:col-span-4"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Create Request</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
