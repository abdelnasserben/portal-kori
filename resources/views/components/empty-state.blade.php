@props([
    'title' => 'No data available.',
    'message' => null,
    'actionLabel' => null,
    'actionHref' => null,
])

<div class="empty-state text-center py-4 px-3">
    <div class="fw-semibold mb-1">{{ $title }}</div>
    @if ($message)
        <div class="text-muted mb-2">{{ $message }}</div>
    @endif
    @if ($actionLabel && $actionHref)
        <a class="btn btn-sm btn-outline-secondary" href="{{ $actionHref }}">{{ $actionLabel }}</a>
    @endif
</div>
