@extends('layouts.app')
@section('title', 'Add Tenant')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Add Tenant</h2>
    <a href="{{ route('tenants.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('tenants.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">User (Tenant Account) *</label>
                    <select name="user_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        <option value="">Select User</option>
                        @foreach(\App\Models\User::where('role','tenant')->get() as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                    <small class="text-xs text-slate-500 mt-1">Create a user account for the tenant first</small>
                </div>
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit *</label>
                    <select name="unit_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->unit_number }} - {{ $u->property->name }} (${{ number_format($u->rent_amount,2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Emergency Contact</label><input type="text" name="emergency_contact_name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('emergency_contact_name') }}"></div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Emergency Phone</label><input type="text" name="emergency_contact_phone" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ old('emergency_contact_phone') }}"></div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                <div class="col-span-12"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Save Tenant</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
