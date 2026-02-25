@props([
    'title' => 'No data available.',
    'message' => null,
])

<div class="empty-state text-center py-4 px-3">
    <div class="fw-semibold mb-1">{{ $title }}</div>
    @if ($message)
        <div class="text-muted">{{ $message }}</div>
    @endif
</div>
