@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Marchand créé</h5>

        <div class="mb-3">
            <div class="fw-semibold">Code</div>
            <div class="mono">
                {{ $created['code'] ?? '' }}
                @if (!empty($created['code']))
                    <x-copy-button :value="$created['code']" />
                @endif
            </div>
        </div>

        <div class="mb-3">
            <div class="fw-semibold">Merchant ID</div>
            <div class="mono">
                {{ $created['merchantId'] ?? '' }}
                @if (!empty($created['merchantId']))
                    <x-copy-button :value="$created['merchantId']" />
                @endif
            </div>
        </div>

        <details class="mb-3">
            <summary class="text-muted">Debug request headers</summary>
            <div class="mono mt-2">Idempotency-Key: {{ $meta['idempotencyKey'] }}</div>
            <div class="mono">X-Correlation-Id: {{ $meta['correlationId'] }}</div>
        </details>

        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('admin.merchants.index') }}">Voir la liste</a>
            <a class="btn btn-outline-secondary" href="{{ route('admin.merchants.create') }}">Créer un autre</a>
        </div>
    </div>
@endsection
