@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Créer un agent</h5>

        <div class="text-muted mb-3">
            Le contrat API ne déclare pas de body pour la création.
            Cette action déclenche <span class="mono">POST /api/v1/agents</span> avec un <span
                class="mono">Idempotency-Key</span>.
        </div>

        <form method="POST" action="{{ route('admin.agents.store') }}">
            @csrf
            <button class="btn btn-primary" type="submit">Créer</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.agents.index') }}">Annuler</a>
        </form>
    </div>
@endsection
