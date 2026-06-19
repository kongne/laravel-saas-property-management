@extends('layouts.app')
@section('title', 'Lease Details')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Lease Details</h2>
    <div class="flex gap-2">
        <a href="{{ route('leases.edit', $lease) }}" class="bg-amber-400 text-white px-4 py-2 rounded-lg hover:bg-amber-500 transition-colors font-medium text-sm inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <a href="{{ route('leases.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">Lease Information</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Tenant:</strong> {{ $lease->tenant->user->name }}</div>
                    <div><strong>Unit:</strong> {{ $lease->unit->unit_number }} ({{ $lease->unit->property->name }})</div>
                    <div><strong>Start:</strong> {{ $lease->start_date->format('M d, Y') }}</div>
                    <div><strong>End:</strong> {{ $lease->end_date->format('M d, Y') }}</div>
                    <div><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $lease->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($lease->status) }}</span></div>
                    <div><strong>Rent:</strong> ${{ number_format($lease->rent_amount, 2) }}/{{ $lease->payment_frequency }}</div>
                    <div><strong>Deposit:</strong> ${{ number_format($lease->security_deposit ?? 0, 2) }}</div>
                    <div><strong>Due Day:</strong> {{ $lease->due_day }} of month</div>
                    @if($lease->terms)
                    <div class="md:col-span-2"><strong>Terms:</strong><br>{{ $lease->terms }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-slate-800">Payments</h5>
                <a href="{{ route('payments.create', ['lease_id' => $lease->id]) }}" class="px-2.5 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50 transition-colors">Record Payment</a>
            </div>
            <div class="p-6">
                @if($lease->payments->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Due</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Paid</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lease->payments as $p)
                            <tr>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $p->invoice_number }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $p->due_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($p->amount,2) }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($p->paid_amount ?? 0,2) }}</td>
                                <td class="px-4 py-3 border-t border-slate-100 text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                                        {{ $p->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($p->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-slate-500">No payments recorded.</p>
                @endif
            </div>
        </div>
    </div>
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h5 class="text-lg font-semibold text-slate-800">Actions</h5>
            </div>
            <div class="p-6 space-y-4">
                @if($lease->status === 'active')
                <x-confirm action="{{ route('leases.terminate', $lease) }}" method="POST" message="Terminate this lease?" confirmText="Terminate" title="Terminate Lease">
                    <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium text-sm w-full">Terminate Lease</button>
                </x-confirm>
                @endif
                <form action="{{ route('leases.renew', $lease) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">New End Date</label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">New Rent</label>
                        <input type="number" name="rent_amount" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" step="0.01" value="{{ $lease->rent_amount }}" required>
                    </div>
                    <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-medium text-sm w-full">Renew Lease</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
