@extends('layouts.app')
@section('title', 'Maintenance Requests')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Maintenance Requests</h2>
    <a href="{{ route('maintenance.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Request
    </a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
            <div class="md:col-span-4">
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <select name="priority" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="emergency" {{ request('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
            </div>
            <div class="md:col-span-2"><button type="submit" class="w-full px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">Filter</button></div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600"><a href="{{ route('maintenance.show', $r) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ $r->title }}</a></td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $r->unit->unit_number }} ({{ $r->unit->property->name }})</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{
                                $r->priority === 'emergency' ? 'bg-red-100 text-red-700' :
                                ($r->priority === 'high' ? 'bg-amber-100 text-amber-700' :
                                'bg-emerald-100 text-emerald-700')
                            }}">{{ ucfirst($r->priority) }}</span>
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{
                                $r->status === 'open' ? 'bg-red-100 text-red-700' :
                                ($r->status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                                'bg-emerald-100 text-emerald-700')
                            }}">{{ ucfirst(str_replace('_',' ',$r->status)) }}</span>
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $r->requested_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('maintenance.show', $r) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 rounded-md hover:bg-sky-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('maintenance.edit', $r) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 rounded-md hover:bg-amber-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500 border-t border-slate-100">No maintenance requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
