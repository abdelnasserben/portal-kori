@props(['items' => []])

<dl class="row mb-0 app-kv-list">
    @foreach ($items as $label => $value)
        <dt class="col-sm-4 text-muted">{{ $label }}</dt>
        <dd class="col-sm-8">{!! $value !== null && $value !== '' ? $value : '—' !!}</dd>
    @endforeach
</dl>
