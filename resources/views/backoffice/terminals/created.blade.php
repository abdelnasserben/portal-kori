@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Terminal créé</h5>

        <div class="mb-3">
            <div class="fw-semibold">Terminal UID</div>
            <div class="mono">
                {{ $created['terminalUid'] ?? '' }}
                @if (!empty($created['terminalUid']))
                    <x-copy-button :value="$created['terminalUid']" />
                @endif
            </div>
        </div>

        <div class="mb-3">
            <div class="fw-semibold">Merchant code</div>
            <div class="mono">
                {{ $meta['merchantCode'] }}
                <x-copy-button :value="$meta['merchantCode']" />
            </div>
        </div>

        <div class="mb-3">
            <div class="fw-semibold">Nom</div>
            <div>{{ $created['displayName'] ?? $meta['displayName'] ?? '' }}</div>
        </div>

        <details class="mb-3">
            <summary class="text-muted">Debug request headers</summary>
            <div class="mono mt-2">Idempotency-Key: {{ $meta['idempotencyKey'] }}</div>
            <div class="mono">X-Correlation-Id: {{ $meta['correlationId'] }}</div>
        </details>

        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('admin.merchants.index') }}">Retour aux marchands</a>
            <a class="btn btn-outline-secondary"
                href="{{ route('admin.terminals.create', ['merchantCode' => $meta['merchantCode']]) }}">
                Créer un autre terminal pour ce marchand
            </a>
        </div>
    </div>
@endsection
