@props([
    'action' => '',
    'method' => 'DELETE',
    'message' => __('Are you sure?'),
    'confirmText' => 'Confirm',
    'cancelText' => __('Cancel'),
    'variant' => 'danger',
    'title' => 'Confirm Action',
])

@php
    $titleId = 'confirm-title-'.uniqid();
    $messageId = 'confirm-message-'.uniqid();
@endphp

<div x-data="{ show: false, trapFocus(e) { if (e.key !== 'Tab') return; const focusable = [...this.$el.querySelectorAll('button:not([disabled]), a[href], input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex=&quot;-1&quot;])')].filter(el => el.offsetParent !== null); if (!focusable.length) return; const first = focusable[0], last = focusable[focusable.length - 1], index = focusable.indexOf(document.activeElement); if (e.shiftKey && index <= 0) { e.preventDefault(); last.focus(); } else if (!e.shiftKey && (index === -1 || index >= focusable.length - 1)) { e.preventDefault(); first.focus(); } } }" class="inline">
    <span @@click="show = true" class="inline cursor-pointer">{{ $slot }}</span>

    <div
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
        aria-labelledby="{{ $titleId }}"
        aria-describedby="{{ $messageId }}"
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
            class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-sm"
        >
            <div class="p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2" id="{{ $titleId }}">{{ $title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6" id="{{ $messageId }}">{{ $message }}</p>
                <div class="flex items-center justify-center gap-3">
                    <button @@click="show = false" type="button" class="btn-secondary btn-sm">{{ $cancelText }}</button>
                    <form action="{{ $action }}" method="POST" class="inline">
                        @csrf @method($method)
                        <button type="submit" class="btn-{{ $variant }} btn-sm">{{ $confirmText }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
