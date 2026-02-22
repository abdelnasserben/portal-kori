@extends('layouts.app')

@section('content')
<div class="card p-4">
    <h5 class="fw-semibold mb-2">Accès refusé</h5>

    <div class="text-muted mb-3">
        Tu n’as pas les droits nécessaires pour accéder à cette page.
    </div>

    <div class="mb-3">
        <div class="fw-semibold">Rôles requis</div>
        <div class="mono">{{ implode(', ', $required ?? []) }}</div>
    </div>

    <div class="mb-3">
        <div class="fw-semibold">Tes rôles</div>
        <div class="mono">{{ implode(', ', $roles ?? []) }}</div>
    </div>

    <a class="btn btn-outline-secondary" href="{{ route('auth.success') }}">Retour</a>
</div>
@endsection