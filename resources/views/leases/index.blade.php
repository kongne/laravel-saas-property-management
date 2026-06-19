@extends('layouts.app')
@section('title', 'Leases')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Leases</h2>
    <a href="{{ route('leases.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Lease
    </a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
            <div class="md:col-span-4"><input type="text" name="search" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search tenant name..." value="{{ request('search') }}"></div>
            <div class="md:col-span-3">
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="md:col-span-2"><button type="submit" class="w-full px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">Filter</button></div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Tenant</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Property</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Rent</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leases as $lease)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600"><a href="{{ route('leases.show', $lease) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ $lease->tenant->user->name }}</a></td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $lease->unit->unit_number }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $lease->unit->property->name }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($lease->rent_amount, 2) }}</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{
                                $lease->status === 'active' ? 'bg-emerald-100 text-emerald-700' :
                                ($lease->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                'bg-slate-100 text-slate-700')
                            }}">{{ ucfirst($lease->status) }}</span>
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('leases.show', $lease) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 rounded-md hover:bg-sky-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('leases.edit', $lease) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 rounded-md hover:bg-amber-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-slate-500 border-t border-slate-100">No leases found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $leases->links() }}
        </div>
    </div>
</div>
@endsection
