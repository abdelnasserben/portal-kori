@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
    'backHref' => null,
    'backLabel' => 'Back',
])

<div class="page-header mb-4">
    @if (!empty($breadcrumbs))
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb app-breadcrumb mb-0">
                @foreach ($breadcrumbs as $crumb)
                    @if (!empty($crumb['href']) && !$loop->last)
                        <li class="breadcrumb-item"><a href="{{ $crumb['href'] }}">{{ $crumb['label'] }}</a></li>
                    @else
                        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if($loop->last) aria-current="page" @endif>
                            {{ $crumb['label'] }}
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
        <div>
            <h1 class="h4 mb-1 fw-semibold">{{ $title }}</h1>
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
</div>
