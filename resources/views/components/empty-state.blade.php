@props(['variant' => 'default', 'title' => null, 'description' => null, 'action' => null])

@php
$illustrations = [
    'default' => '<svg aria-hidden="true" class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>',
    'search' => '<svg aria-hidden="true" class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11a5 5 0 10-10 0 5 5 0 0010 0z"/></svg>',
    'error' => '<svg aria-hidden="true" class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
];
$message = $message ?? null;
@endphp

<div class="flex flex-col items-center justify-center py-12 px-4 text-center">
    {!! $illustrations[$variant] ?? $illustrations['default'] !!}
    @if($title)
    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-1">{{ $title }}</h3>
    @elseif($message)
    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-1">{{ $message }}</h3>
    @endif
    @if($description)
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">{{ $description }}</p>
    @endif
    @if($action)
    <div class="mt-4">{{ $action }}</div>
    @endif
</div>
