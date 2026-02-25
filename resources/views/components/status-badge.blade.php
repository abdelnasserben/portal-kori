@props(['value' => null])

@php
    $raw = (string) ($value ?? '');
    $normalized = strtoupper($raw);

    $class = match ($normalized) {
        'ACTIVE', 'SUCCESS', 'COMPLETED', 'APPROVED' => 'text-bg-success',
        'PENDING', 'INITIATED', 'PROCESSING' => 'text-bg-warning',
        'SUSPENDED', 'FAILED', 'REJECTED', 'CLOSED', 'CANCELLED' => 'text-bg-danger',
        default => 'text-bg-secondary',
    };

    $label = trim(str_replace('_', ' ', strtolower($raw)));
    $label = $label !== '' ? ucwords($label) : 'N/A';
@endphp

<span class="badge {{ $class }}">{{ $label }}</span>
