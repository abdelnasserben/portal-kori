@props([
    'name',
    'label' => null,
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

<textarea id="{{ $fieldId }}" name="{{ $name }}"
    @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
    @if ($required) required @endif @if ($disabled) disabled @endif
    @if ($readonly) readonly @endif {{ $attributes->merge(['class' => 'form-control']) }}>{{ old($name, $value) }}</textarea>

@if ($help)
    <div class="form-text">{{ $help }}</div>
@endif

@error($name)
    <div class="text-danger small">{{ $message }}</div>
@enderror
