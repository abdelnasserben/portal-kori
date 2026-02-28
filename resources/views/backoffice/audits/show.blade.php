@extends('layouts.app')

@section('content')
<div class="card panel p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold mb-0">Audit details</h5>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.audits.index') }}">Back to list</a>
    </div>

    @if(empty($item))
        <div class="alert alert-warning mb-0">
            Unable to load this audit (eventRef: <span class="mono">{{ $eventRef }}</span>).
        </div>
    @else
        <dl class="row mb-0">
            <dt class="col-sm-3">Event Ref</dt>
            <dd class="col-sm-9 mono">{{ $item['eventRef'] ?? $eventRef }}</dd>

            <dt class="col-sm-3">Occurred At</dt>
            <dd class="col-sm-9">@dateIso($item['occurredAt'] ?? null, '—')</dd>

            <dt class="col-sm-3">Actor</dt>
            <dd class="col-sm-9"><span class="badge text-bg-secondary">{{ $item['actorType'] ?? '—' }}</span> <span class="mono">{{ $item['actorRef'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Action</dt>
            <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['action'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Resource</dt>
            <dd class="col-sm-9 mono">{{ $item['resourceType'] ?? '—' }} / {{ $item['resourceRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Metadata</dt>
            <dd class="col-sm-9">
                @if(is_array($item['metadata'] ?? null) && !empty($item['metadata']))
                    <pre class="mb-0"><code>{{ json_encode($item['metadata'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
                @else
                    —
                @endif
            </dd>
        </dl>
    @endif
</div>
@endsection
