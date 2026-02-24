@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
])

@php
    $fieldId = $attributes->get('id', str_replace(['[', ']', '.'], '_', $name));
    $selectedValue = (string) old($name, $value);
@endphp

@if ($label)
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<select id="{{ $fieldId }}" name="{{ $name }}" @if ($required) required @endif
    @if ($disabled) disabled @endif
    @if ($readonly) disabled data-readonly="true" @endif
    {{ $attributes->merge(['class' => 'form-select']) }}>
    @if ($placeholder !== null)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach ($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" @selected((string) $optionValue === $selectedValue)>
            {{ $optionLabel }}
        </option>
    @endforeach
</select>

@error($name)
    <div class="text-danger small">{{ $message }}</div>
@enderror
