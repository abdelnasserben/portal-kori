@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Agent créé</h5>

        <div class="mb-3">
            <div class="fw-semibold">Agent code</div>
            <div class="mono">
                {{ $created['agentCode'] ?? '' }}
                @if (!empty($created['agentCode']))
                    <x-copy-button :value="$created['agentCode']" />
                @endif
            </div>
        </div>

        <div class="mb-3">
            <div class="fw-semibold">Nom</div>
            <div>{{ $created['displayName'] ?? $meta['displayName'] ?? '' }}</div>
        </div>

        <div class="mb-3">
            <div class="fw-semibold">Agent ID</div>
            <div class="mono">
                {{ $created['agentId'] ?? '' }}
                @if (!empty($created['agentId']))
                    <x-copy-button :value="$created['agentId']" />
                @endif
            </div>
        </div>

        <details class="mb-3">
            <summary class="text-muted">Debug request headers</summary>
            <div class="mono mt-2">Idempotency-Key: {{ $meta['idempotencyKey'] }}</div>
            <div class="mono">X-Correlation-Id: {{ $meta['correlationId'] }}</div>
        </details>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('admin.agents.create') }}">Créer un autre</a>
            <a class="btn btn-outline-secondary" href="{{ route('admin.agents.index') }}">Voir la liste</a>
        </div>
    </div>
@endsection
