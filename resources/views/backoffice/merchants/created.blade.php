@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Merchant created</h5>

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
            <div class="fw-semibold">Nom</div>
            <div>{{ $created['displayName'] ?? $meta['displayName'] ?? '' }}</div>
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

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('admin.merchants.index') }}">View list</a>
            @if (!empty($created['code']))
                <a class="btn btn-outline-primary"
                    href="{{ route('admin.terminals.create', ['merchantCode' => $created['code']]) }}">
                    Create a terminal for this merchant
                </a>
            @endif
            <a class="btn btn-outline-secondary" href="{{ route('admin.merchants.create') }}">Create another</a>
        </div>
    </div>
@endsection
