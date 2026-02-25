@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Merchant Area</h5>
        <div class="text-muted mb-3">Merchant self-service based on endpoints <code>/merchant/me/**</code>.</div>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('merchant.me.transactions') }}">Transactions</a>
            <a class="btn btn-outline-primary" href="{{ route('merchant.me.terminals') }}">Terminals</a>
            <a class="btn btn-outline-primary" href="{{ route('merchant.me.profile') }}">Profile</a>
            <a class="btn btn-outline-primary" href="{{ route('merchant.me.balance') }}">Balance</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Profile</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-5 text-muted">Code</dt>
                    <dd class="col-7 mono">{{ $profile['code'] ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Phone</dt>
                    <dd class="col-7">{{ $profile['phone'] ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Status</dt>
                    <dd class="col-7"><span class="badge text-bg-light">{{ $profile['status'] ?? '—' }}</span></dd>
                </dl>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Balance</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-5 text-muted">Account</dt>
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
