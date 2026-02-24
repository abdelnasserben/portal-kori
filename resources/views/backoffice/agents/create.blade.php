@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Créer un agent</h5>

        <div class="text-muted mb-3">
            Selon le contrat OpenAPI, la création d'agent passe par <span class="mono">POST /api/v1/agents</span>
            avec un body contenant <span class="mono">displayName</span>.
        </div>

        <form method="POST" action="{{ route('admin.agents.store') }}" class="row g-3">
            @csrf
            <div class="col-12 col-md-6">
                <x-form.input name="displayName" label="Nom" maxlength="120" required />
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.agents.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
