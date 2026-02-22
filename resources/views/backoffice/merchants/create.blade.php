@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Créer un marchand</h5>

        <div class="text-muted mb-3">
            Le contrat API ne déclare pas de body pour la création.
            Cette action déclenche <span class="mono">POST /api/v1/merchants</span> avec un <span
                class="mono">Idempotency-Key</span>.
        </div>

        <form method="POST" action="{{ route('admin.merchants.store') }}">
            @csrf
            <button class="btn btn-primary" type="submit">Créer</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.merchants.index') }}">Annuler</a>
        </form>
    </div>
@endsection
