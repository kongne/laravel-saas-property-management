@extends('layouts.app')
@section('title', 'Edit Tenant')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Tenant</h2>
    <a href="{{ route('tenants.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('tenants.update', $tenant) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">User</label>
                    <select name="user_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(\App\Models\User::where('role','tenant')->get() as $u)
                            <option value="{{ $u->id }}" {{ $tenant->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit</label>
                    <select name="unit_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $tenant->unit_id == $u->id ? 'selected' : '' }}>{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Emergency Contact</label><input type="text" name="emergency_contact_name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $tenant->emergency_contact_name }}"></div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Emergency Phone</label><input type="text" name="emergency_contact_phone" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $tenant->emergency_contact_phone }}"></div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['pending','active','past'] as $s)
                            <option value="{{ $s }}" {{ $tenant->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12"><label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label><textarea name="notes" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="2">{{ $tenant->notes }}</textarea></div>
                <div class="col-span-12"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Update Tenant</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
