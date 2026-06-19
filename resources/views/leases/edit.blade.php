@extends('layouts.app')
@section('title', 'Edit Lease')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit Lease</h2>
    <a href="{{ route('leases.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form action="{{ route('leases.update', $lease) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit</label>
                    <select name="unit_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $lease->unit_id == $u->id ? 'selected' : '' }}>{{ $u->unit_number }} - {{ $u->property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tenant</label>
                    <select name="tenant_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ $lease->tenant_id == $t->id ? 'selected' : '' }}>{{ $t->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Start Date</label><input type="date" name="start_date" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $lease->start_date->format('Y-m-d') }}"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">End Date</label><input type="date" name="end_date" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $lease->end_date->format('Y-m-d') }}"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Rent Amount</label><input type="number" name="rent_amount" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $lease->rent_amount }}" step="0.01"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Security Deposit</label><input type="number" name="security_deposit" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $lease->security_deposit }}" step="0.01"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Frequency</label>
                    <select name="payment_frequency" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['monthly','quarterly','yearly'] as $f)
                            <option value="{{ $f }}" {{ $lease->payment_frequency === $f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Due Day</label><input type="number" name="due_day" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="{{ $lease->due_day }}" min="1" max="31"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach(['pending','active','expired','terminated'] as $s)
                            <option value="{{ $s }}" {{ $lease->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4"><label class="block text-sm font-medium text-slate-700 mb-1.5">Terms</label><textarea name="terms" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" rows="3">{{ $lease->terms }}</textarea></div>
                <div class="md:col-span-4"><button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Update Lease</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
