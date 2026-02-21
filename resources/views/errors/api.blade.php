@extends('layouts.app')

@section('content')
<div class="card p-4">
    <h5 class="fw-semibold mb-2">Erreur lors de l’appel à l’API</h5>

    <div class="mb-3">
        <span class="badge text-bg-danger">HTTP {{ $error['status'] ?? 'N/A' }}</span>
    </div>

    <div class="mb-3">
        {{ $error['message'] ?? 'Une erreur est survenue.' }}
    </div>

    <div class="text-muted" style="font-size: 0.9rem;">
        Astuce : tant que l’auth Keycloak n’est pas en place, un <strong>401</strong> est attendu.
    </div>
</div>
@endsection