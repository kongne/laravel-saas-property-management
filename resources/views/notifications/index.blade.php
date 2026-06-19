@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Notifications</h2>
    @if(Auth::user()->unreadNotifications->count())
    <form action="{{ route('notifications.read-all') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">Mark All as Read</button>
    </form>
    @endif
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="divide-y divide-slate-200">
        @forelse($notifications as $notification)
        <div class="p-4 {{ $notification->read_at ? '' : 'bg-slate-50' }}">
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1">
                    <strong class="text-sm font-semibold text-slate-800">{{ $notification->data['title'] ?? 'Notification' }}</strong>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $notification->data['message'] ?? '' }}</p>
                    <small class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="shrink-0 ml-3">
                    @csrf
                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm underline">Mark read</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-slate-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p>No notifications yet.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endsection
