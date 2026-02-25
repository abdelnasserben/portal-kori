@props([
    'title',
    'subtitle' => null,
    'backHref' => null,
    'backLabel' => 'Back',
])

<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 mb-1">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="d-flex align-items-center gap-2">
        {{ $actions ?? '' }}
        @if ($backHref)
            <a class="btn btn-sm btn-outline-secondary" href="{{ $backHref }}">{{ $backLabel }}</a>
        @endif
    </div>
</div>
