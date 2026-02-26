@props(['items' => []])

<div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
    @foreach ($items as $label => $value)
        <span><strong class="fw-medium">{{ $label }}:</strong> {{ $value ?: '—' }}</span>
    @endforeach
</div>
