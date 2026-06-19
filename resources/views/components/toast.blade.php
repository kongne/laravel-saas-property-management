@props(['type' => 'success', 'message' => '', 'id' => null])

@php
$config = [
    'success' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/30', 'border' => 'border-emerald-200 dark:border-emerald-800', 'text' => 'text-emerald-700 dark:text-emerald-300', 'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
    'error' => ['bg' => 'bg-red-50 dark:bg-red-900/30', 'border' => 'border-red-200 dark:border-red-800', 'text' => 'text-red-700 dark:text-red-300', 'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
    'warning' => ['bg' => 'bg-amber-50 dark:bg-amber-900/30', 'border' => 'border-amber-200 dark:border-amber-800', 'text' => 'text-amber-700 dark:text-amber-300', 'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
    'info' => ['bg' => 'bg-sky-50 dark:bg-sky-900/30', 'border' => 'border-sky-200 dark:border-sky-800', 'text' => 'text-sky-700 dark:text-sky-300', 'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
];
$c = $config[$type] ?? $config['success'];
$toastId = $id ?? 'toast-'.uniqid();
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full" id="{{ $toastId }}" class="toast-enter flex items-center gap-2 {{ $c['bg'] }} {{ $c['border'] }} border {{ $c['text'] }} px-4 py-3 rounded-lg shadow-lg max-w-sm w-full" role="alert" aria-live="polite">
    {!! $c['icon'] !!}
    <span class="flex-1 text-sm">{{ $message }}</span>
    <button @click="show = false" class="{{ $c['text'] }} hover:opacity-70 p-0.5 rounded transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
