@props(['value' => null])

@php
    $raw = strtoupper((string) ($value ?? 'UNKNOWN'));

    $class = match ($raw) {
        'ACTIVE', 'COMPLETED', 'SUCCESS' => 'status-badge--success',
        'REQUESTED', 'PENDING', 'PROCESSING', 'INITIATED' => 'status-badge--warning',
        'SUSPENDED', 'FAILED', 'CLOSED', 'REJECTED', 'CANCELLED' => 'status-badge--danger',
        default => 'status-badge--neutral',
    };
@endphp

<span class="status-badge {{ $class }}">{{ $raw }}</span>
