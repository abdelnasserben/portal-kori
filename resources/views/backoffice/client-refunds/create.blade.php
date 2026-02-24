@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Demander un remboursement client</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">← Retour admin</a>
        </div>

        <p class="text-muted small">
            Cette action appelle <span class="mono">POST /api/v1/client-refunds/requests</span>.
            Le montant est calculé côté API selon la logique métier.
        </p>

        <form method="POST" action="{{ route('admin.client-refunds.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Code client</label>
                <input name="clientCode" class="form-control @error('clientCode') is-invalid @enderror"
                    value="{{ old('clientCode', $prefillClientCode ?? '') }}" required maxlength="16"
                    placeholder="CL-0001">
                @error('clientCode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer la demande</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.clients.index') }}">Voir les clients</a>
            </div>
        </form>
    </div>
@endsection
