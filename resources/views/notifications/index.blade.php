@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Notifications</h2>
    @if(Auth::user()->unreadNotifications->count())
    <form action="{{ route('notifications.read-all') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">Mark All as Read</button>
    </form>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($notifications as $notification)
        <div class="notification-item p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                    <p class="mb-1 text-muted small">{{ $notification->data['message'] ?? '' }}</p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-decoration-none">Mark read</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell fs-1 d-block mb-2"></i>
            <p>No notifications yet.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-3">
    {{ $notifications->links() }}
</div>
@endsection

@push('styles')
<style>
    .notification-item:last-child { border-bottom: none !important; }
    .notification-item:hover { background: #f8f9fa; }
</style>
@endpush
