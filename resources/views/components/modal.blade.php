@props([
    'show' => false,
    'maxWidth' => 'sm',
    'title' => null,
])

@php
    $widths = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-xl', '2xl' => 'max-w-2xl'];
    $width = $widths[$maxWidth] ?? 'max-w-md';
    $titleId = 'modal-title-'.uniqid();
@endphp

<div
    x-data="{
        show: @js($show),
        trapFocus(e) {
            if (e.key !== 'Tab') return;
            const focusable = [...this.$el.querySelectorAll('button:not([disabled]), a[href], input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex=&quot;-1&quot;])')].filter(el => el.offsetParent !== null);
            if (!focusable.length) return;
            const first = focusable[0], last = focusable[focusable.length - 1], index = focusable.indexOf(document.activeElement);
            if (e.shiftKey && index <= 0) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && (index === -1 || index >= focusable.length - 1)) { e.preventDefault(); first.focus(); }
        }
    }"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape="show = false"
    @keydown.tab.prevent="trapFocus($event)"
    x-effect="show && $nextTick(() => $el.querySelector('button')?.focus())"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    @if($title) aria-labelledby="{{ $titleId }}" @endif
>
    <div @@click="show = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full {{ $width }}"
    >
        @if($title)
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200" id="{{ $titleId }}">{{ $title }}</h3>
            <button @@click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
