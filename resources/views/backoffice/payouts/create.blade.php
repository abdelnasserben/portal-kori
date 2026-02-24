@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Demander un payout agent</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">← Retour admin</a>
        </div>

        <p class="text-muted small">
            Cette action appelle <span class="mono">POST /api/v1/payouts/requests</span>.
            Le montant est calculé côté API selon la logique métier.
        </p>

        <form method="POST" action="{{ route('admin.payouts.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Code agent</label>
                <input name="agentCode" class="form-control @error('agentCode') is-invalid @enderror"
                    value="{{ old('agentCode', $prefillAgentCode ?? '') }}" required maxlength="16"
                    placeholder="AG-0001">
                @error('agentCode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer la demande</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.agents.index') }}">Voir les agents</a>
            </div>
        </form>
    </div>
@endsection
