@props(['label' => null, 'help' => null, 'required' => false])

<div {{ $attributes->only('class') }}>
    @if($label)
    <x-forms.label :required="$required">{{ $label }}</x-forms.label>
    @endif
    {{ $slot }}
    @if($help)
    <p class="text-xs text-slate-400 mt-1">{{ $help }}</p>
    @endif
</div>
