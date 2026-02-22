@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Admin créé</h5>

        <div class="mb-3">
            <div class="fw-semibold">Admin ID</div>
            <div class="mono">
                {{ $created['adminId'] ?? '' }}
                @if (!empty($created['adminId']))
                    <x-copy-button :value="$created['adminId']" />
                @endif
            </div>
        </div>

        <div class="mb-3">
            <div class="fw-semibold">Username</div>
            <div class="mono">
                {{ $meta['username'] }}
                <x-copy-button :value="$meta['username']" />
            </div>
        </div>

        <details class="mb-3">
            <summary class="text-muted">Debug request headers</summary>
            <div class="mono mt-2">Idempotency-Key: {{ $meta['idempotencyKey'] }}</div>
            <div class="mono">X-Correlation-Id: {{ $meta['correlationId'] }}</div>
        </details>

        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('admin.home') }}">Retour admin</a>
            <a class="btn btn-outline-secondary" href="{{ route('admin.admins.create') }}">Créer un autre admin</a>
        </div>
    </div>
@endsection
