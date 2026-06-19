@props([
    'name' => null,
    'label' => null,
    'id' => null,
    'value' => '1',
    'checked' => false,
    'disabled' => false,
])

@php
    $id = $id ?? $name;
@endphp

<label {{ $attributes->only('class') }} class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value }}"
        @if($disabled) disabled @endif
        class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800"
        {{ old($name) ? 'checked' : ($checked ? 'checked' : '') }}
    >
    <span>{{ $label ?: $slot }}</span>
</label>
