@props([
    'type' => 'submit',
    'variant' => 'primary',
    'size' => 'default',
    'disabled' => false,
    'loading' => false,
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
    ];
    $sizes = [
        'sm' => 'btn-sm',
        'default' => '',
        'lg' => 'px-6 py-3 text-base',
    ];
    $class = ($variants[$variant] ?? 'btn-primary') . ' ' . ($sizes[$size] ?? '');
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $class]) }}
    @if($disabled) disabled @endif
    @if($loading) aria-busy="true" @endif
>
    @if($loading)
    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    @endif
    {{ $slot }}
</button>
