@extends('layouts.app')

@section('title', __('Notifications'))

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Notifications') }}</h2>
    @if(Auth::user()->unreadNotifications->count())
    <form action="{{ route('notifications.read-all') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">{{ __('Mark All as Read') }}</button>
    </form>
    @endif
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="divide-y divide-slate-200">
        @forelse($notifications as $notification)
        <div class="p-4 {{ $notification->read_at ? '' : 'bg-slate-50' }}">
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1">
                    <strong class="text-sm font-semibold text-slate-800">{{ $notification->data['title'] ?? __('Notification') }}</strong>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $notification->data['message'] ?? '' }}</p>
                    <small class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="shrink-0 ml-3">
                    @csrf
                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm underline">{{ __('Mark read') }}</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="py-12">
            <x-empty-state type="default" :title="__('No notifications')" :message="__(\"You're all caught up! Notifications will appear here.\")" />
        </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    <x-pagination :paginator="$notifications" />
</div>
@endsection
