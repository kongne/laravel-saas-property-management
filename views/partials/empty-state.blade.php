<div class="empty-state">
    <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="20" y="30" width="80" height="60" rx="8" stroke="currentColor" stroke-width="2" fill="none"/>
        <path d="M35 55h50M35 65h30M35 75h40" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <circle cx="90" cy="25" r="16" stroke="currentColor" stroke-width="2" fill="none"/>
        <path d="M90 20v10M85 25h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <h6 class="fw-bold mb-2">{{ $title ?? 'Nothing here yet' }}</h6>
    <p class="text-muted small mb-3">{{ $message ?? '' }}</p>
    @if(isset($actionUrl) && isset($actionLabel))
    <a href="{{ $actionUrl }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> {{ $actionLabel }}</a>
    @endif
</div>
