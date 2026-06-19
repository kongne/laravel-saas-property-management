@props([
    'name' => null,
    'label' => null,
    'id' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $id = $id ?? $name;
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
    <x-forms.label :for="$id" :required="$required">{{ $label }}</x-forms.label>
    @endif
    @php $hasError = $errors->has($name); $errorId = \Illuminate\Support\Str::kebab($name).'-error'; @endphp
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        @if($required) required aria-required="true" @endif
        @if($disabled) disabled @endif
        @if($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
        class="select @error($name) border-red-500 @enderror"
    >
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $val => $label)
        <option value="{{ $val }}" {{ old($name, $value) == $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <x-forms.error :field="$name" />
</div>
