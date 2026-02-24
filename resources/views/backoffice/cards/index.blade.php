@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Gestion des cartes</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">← Retour admin</a>
        </div>

        <p class="text-muted mb-0" style="font-size:.9rem;">Actions ADMIN sur les cartes via l’API.</p>
    </div>

    <div class="card p-4 mb-3">
        <h6 class="fw-semibold mb-3">Changer le statut d'une carte</h6>

        <form method="POST" action="{{ route('admin.cards.status.update') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Card UID</label>
                <input name="cardUid" class="form-control form-control-sm" required maxlength="120" placeholder="CARD-...">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Nouveau statut</label>
                <input name="targetStatus" class="form-control form-control-sm" required maxlength="50"
                    placeholder="ACTIVE, SUSPENDED, BLOCKED...">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Raison (optionnel)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" placeholder="Raison">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-sm btn-outline-primary" type="submit">Changer statut</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-3">Débloquer une carte</h6>

        <form method="POST" action="{{ route('admin.cards.unblock') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Card UID</label>
                <input name="cardUid" class="form-control form-control-sm" required maxlength="120" placeholder="CARD-...">
            </div>
            <div class="col-6 col-md-6">
                <label class="form-label mb-1">Raison (optionnel)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" placeholder="Raison">
            </div>
            <div class="col-6 col-md-2 d-grid">
                <button class="btn btn-sm btn-outline-warning" type="submit">Débloquer</button>
            </div>
        </form>
    </div>
@endsection
