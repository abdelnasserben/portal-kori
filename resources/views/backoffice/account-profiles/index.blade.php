@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Gestion des profiles de compte</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">← Retour admin</a>
        </div>

        <p class="text-muted mb-1" style="font-size:.9rem;">Action d'administration via le contrat OpenAPI.</p>
        <div class="text-muted" style="font-size:.85rem;">PATCH <span class="mono">/api/v1/account-profiles/status</span>
        </div>
    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-3">Modifier le statut d'un profile</h6>

        <form method="POST" action="{{ route('admin.account-profiles.status.update') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Type de compte</label>
                <input name="accountType" class="form-control form-control-sm" required maxlength="50"
                    placeholder="CLIENT, AGENT, MERCHANT..." value="{{ old('accountType') }}">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Owner ref</label>
                <input name="ownerRef" class="form-control form-control-sm" required maxlength="120"
                    placeholder="actorRef / ownerRef" value="{{ old('ownerRef') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Nouveau statut</label>
                <input name="targetStatus" class="form-control form-control-sm" required maxlength="50"
                    placeholder="ACTIVE, SUSPENDED..." value="{{ old('targetStatus') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Raison (optionnel)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" placeholder="Raison"
                    value="{{ old('reason') }}">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-sm btn-outline-primary" type="submit">Changer statut</button>
            </div>
        </form>
    </div>
@endsection
