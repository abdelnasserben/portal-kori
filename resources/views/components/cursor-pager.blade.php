@props([
    'hasMore' => false,
    'nextCursor' => null,
    'nextHref' => null,
    'routeName' => null,
    'filters' => [],
    'label' => 'Next',
])

@php
    $url = $nextHref;
    if (!$url && $routeName && $hasMore && $nextCursor) {
        $url = route($routeName, array_merge($filters, ['cursor' => $nextCursor]));
    }
@endphp

@if ($hasMore && $url)
    <a class="btn btn-sm btn-outline-primary" href="{{ $url }}">{{ $label }}</a>
@else
    <button class="btn btn-sm btn-outline-secondary" disabled>{{ $label }}</button>
@endif
