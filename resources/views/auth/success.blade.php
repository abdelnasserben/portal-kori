@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Connexion Keycloak réussie</h5>

        <div class="text-muted mb-3">
            Tu es connecté au portail. Les tokens sont stockés en session Laravel.
            <br>
            L’accès API peut encore échouer tant que les claims attendus par l’API ne sont pas configurés.
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('health') }}">Tester l’API (/health)</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
        </div>
    </div>
@endsection
