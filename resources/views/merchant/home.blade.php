@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Espace Merchant</h5>
        <div class="text-muted mb-3">Self-service marchand basé sur les endpoints <code>/merchant/me/**</code>.</div>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('merchant.me.transactions') }}">Transactions</a>
            <a class="btn btn-outline-primary" href="{{ route('merchant.me.terminals') }}">Terminaux</a>
            <a class="btn btn-outline-primary" href="{{ route('merchant.me.profile') }}">Profil</a>
            <a class="btn btn-outline-primary" href="{{ route('merchant.me.balance') }}">Solde</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Profil</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-5 text-muted">Code</dt>
                    <dd class="col-7 mono">{{ $profile['code'] ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Téléphone</dt>
                    <dd class="col-7">{{ $profile['phone'] ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Statut</dt>
                    <dd class="col-7"><span class="badge text-bg-light">{{ $profile['status'] ?? '—' }}</span></dd>
                </dl>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Solde</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-5 text-muted">Compte</dt>
                    <dd class="col-7">{{ $balance['accountType'] ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Owner</dt>
                    <dd class="col-7 mono">{{ $balance['ownerRef'] ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Balance</dt>
                    <dd class="col-7 mono">{{ $balance['balance'] ?? '—' }} {{ $balance['currency'] ?? '' }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
