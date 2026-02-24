@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
])

@php
    $fieldId = $attributes->get('id', str_replace(['[', ']', '.'], '_', $name));
@endphp

@if ($label)
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<input id="{{ $fieldId }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
    @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
    @if ($required) required @endif @if ($disabled) disabled @endif
    @if ($readonly) readonly @endif {{ $attributes->merge(['class' => 'form-control']) }}>

@if ($help)
    <div class="form-text">{{ $help }}</div>
@endif

@error($name)
    <div class="text-danger small">{{ $message }}</div>
@enderror
