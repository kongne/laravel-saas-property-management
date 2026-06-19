@extends('layouts.app')
@section('title', 'Payments')
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Payments</h2>
    <div class="flex items-center gap-4 flex-wrap">
        <span class="text-emerald-600 text-sm"><strong>Collected:</strong> ${{ number_format($totalCollected, 2) }}</span>
        <span class="text-red-600 text-sm"><strong>Due:</strong> ${{ number_format($totalDue, 2) }}</span>
        <a href="{{ route('payments.create') }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Record Payment
        </a>
    </div>
</div>

<x-table title="Payments" :headers="['invoice' => 'Invoice', 'tenant' => 'Tenant', 'unit' => 'Unit', 'amount' => 'Amount', 'paid' => 'Paid', 'due' => 'Due', 'status' => 'Status', 'actions' => 'Actions']">
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <select name="status" class="select !w-28 !py-1.5 !text-xs">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input type="date" name="date_from" class="input !w-36 !py-1.5 !text-xs" placeholder="From" value="{{ request('date_from') }}">
            <input type="date" name="date_to" class="input !w-36 !py-1.5 !text-xs" placeholder="To" value="{{ request('date_to') }}">
            <button type="submit" class="btn-secondary btn-sm">Filter</button>
        </form>
    </x-slot>
    @forelse($payments as $payment)
    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 font-medium">{{ $payment->invoice_number }}</a></td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $payment->tenant->user->name ?? 'N/A' }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $payment->unit->unit_number ?? 'N/A' }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">${{ number_format($payment->amount, 2) }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">${{ number_format($payment->paid_amount ?? 0, 2) }}</td>
        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $payment->due_date->format('M d, Y') }}</td>
        <td class="px-4 py-3">
            <span class="badge {{
                $payment->status === 'paid' ? 'badge-success' :
                ($payment->status === 'overdue' ? 'badge-danger' :
                ($payment->status === 'partial' ? 'badge-info' :
                'badge-warning'))
            }}">{{ ucfirst($payment->status) }}</span>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-1">
                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center justify-center w-8 h-8 text-sky-600 dark:text-sky-400 rounded-md hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('payments.receipt', $payment) }}" class="inline-flex items-center justify-center w-8 h-8 text-slate-600 dark:text-slate-400 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                </a>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-4 py-12 text-center">
            <x-empty-state message="No payments found." />
        </td>
    </tr>
    @endforelse
    <x-slot name="footer">
        <x-pagination :paginator="$payments" />
    </x-slot>
</x-table>
@endsection