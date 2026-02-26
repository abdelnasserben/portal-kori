@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Session active</h5>

        <div class="text-muted mb-3">
            Authentication was successful, but the automatic redirection to a portal dashboard could not be completed.
            Please verify that the required role is present in the token.
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('post-login.redirect') }}">Retry redirection</a>
            <a class="btn btn-outline-secondary" href="{{ route('health') }}">Test API (/health)</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
        </div>
    </div>
@endsection
