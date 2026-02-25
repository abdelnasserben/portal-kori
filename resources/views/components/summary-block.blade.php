@props(['items' => []])

<div class="panel">
    <dl class="row mb-0">
        @foreach ($items as $item)
            <dt class="col-sm-4 text-muted">{{ $item['label'] ?? '' }}</dt>
            <dd class="col-sm-8">{!! $item['value'] ?? '—' !!}</dd>
        @endforeach
    </dl>
</div>
