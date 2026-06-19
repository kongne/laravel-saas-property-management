@props(['field' => null])

@php
    $errorId = $field ? \Illuminate\Support\Str::kebab($field).'-error' : null;
@endphp

@error($field)
<p id="{{ $errorId }}" class="text-red-500 text-xs mt-1" role="alert">{{ $message }}</p>
@enderror
