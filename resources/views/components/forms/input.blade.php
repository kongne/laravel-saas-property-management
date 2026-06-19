@props([
    'name' => null,
    'label' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'help' => null,
])

@php
    $id = $id ?? $name;
    $errorKey = $name ? \Illuminate\Support\Str::kebab($name) : null;
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
    <x-forms.label :for="$id" :required="$required">{{ $label }}</x-forms.label>
    @endif
    @php $hasError = $errors->has($name); $errorId = \Illuminate\Support\Str::kebab($name).'-error'; @endphp
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required aria-required="true" @endif
        @if($disabled) disabled @endif
        @if($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
        class="input @error($name) border-red-500 @enderror"
    >
    @if($help)
    <p class="text-xs text-slate-400 mt-1">{{ $help }}</p>
    @endif
    <x-forms.error :field="$name" />
</div>
