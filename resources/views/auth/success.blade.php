@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Keycloak sign-in successful</h5>

        <div class="text-muted mb-3">
            You are signed in to the portal. Tokens are stored in the Laravel session.
            <br>
            API access can still fail until the claims expected by the API are configured.
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('health') }}">Test API (/health)</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
        </div>
    </div>
@endsection
