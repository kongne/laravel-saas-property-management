@extends('layouts.app')
@section('title', 'Payments')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Payments</h2>
    <div class="flex items-center gap-4">
        <span class="text-emerald-600 text-sm"><strong>Collected:</strong> ${{ number_format($totalCollected, 2) }}</span>
        <span class="text-red-600 text-sm"><strong>Due:</strong> ${{ number_format($totalDue, 2) }}</span>
        <a href="{{ route('payments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Record Payment
        </a>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
            <div class="md:col-span-3">
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="md:col-span-3"><input type="date" name="date_from" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="From" value="{{ request('date_from') }}"></div>
            <div class="md:col-span-3"><input type="date" name="date_to" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="To" value="{{ request('date_to') }}"></div>
            <div class="md:col-span-2"><button type="submit" class="w-full px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">Filter</button></div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Tenant</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Paid</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Due</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600"><a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ $payment->invoice_number }}</a></td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $payment->tenant->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $payment->unit->unit_number ?? 'N/A' }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">${{ number_format($payment->paid_amount ?? 0, 2) }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $payment->due_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{
                                $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' :
                                ($payment->status === 'overdue' ? 'bg-red-100 text-red-700' :
                                ($payment->status === 'partial' ? 'bg-sky-100 text-sky-700' :
                                'bg-amber-100 text-amber-700'))
                            }}">{{ ucfirst($payment->status) }}</span>
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 rounded-md hover:bg-sky-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('payments.receipt', $payment) }}" class="inline-flex items-center justify-center w-8 h-8 text-slate-600 rounded-md hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-slate-500 border-t border-slate-100">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
