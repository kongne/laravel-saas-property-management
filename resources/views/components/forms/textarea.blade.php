@props([
    'name' => null,
    'label' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'rows' => 4,
    'required' => false,
    'disabled' => false,
    'help' => null,
])

@php
    $id = $id ?? $name;
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
    <x-forms.label :for="$id" :required="$required">{{ $label }}</x-forms.label>
    @endif
    @php $hasError = $errors->has($name); $errorId = \Illuminate\Support\Str::kebab($name).'-error'; @endphp
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required aria-required="true" @endif
        @if($disabled) disabled @endif
        @if($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
        class="input @error($name) border-red-500 @enderror"
    >{{ old($name, $value) }}</textarea>
    @if($help)
    <p class="text-xs text-slate-400 mt-1">{{ $help }}</p>
    @endif
    <x-forms.error :field="$name" />
</div>
