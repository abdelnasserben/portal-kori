@extends('layouts.app')

@section('content')
    <h2 class="fw-semibold mb-4">Dashboard</h2>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Profile</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-5 text-muted">Code</dt>
                    <dd class="col-7 mono">{{ data_get($dashboard, 'profile.code', '—') }}</dd>

                    <dt class="col-5 text-muted">Name</dt>
                    <dd class="col-7">{{ data_get($dashboard, 'profile.displayName', '—') }}</dd>

                    <dt class="col-5 text-muted">Status</dt>
                    <dd class="col-7"><span class="badge text-bg-light">{{ data_get($dashboard, 'profile.status', '—') }}</span></dd>
                </dl>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Balance</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-5 text-muted">Account</dt>
                    <dd class="col-7">{{ data_get($dashboard, 'balance.accountType', '—') }}</dd>

                    <dt class="col-5 text-muted">Owner</dt>
                    <dd class="col-7 mono">{{ data_get($dashboard, 'balance.ownerRef', '—') }}</dd>

                    <dt class="col-5 text-muted">Balance</dt>
                    <dd class="col-7 mono">{{ data_get($dashboard, 'balance.balance', '—') }} {{ data_get($dashboard, 'balance.currency', '') }}</dd>
                </dl>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">KPIs 7j</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-7 text-muted">Transactions count</dt>
                    <dd class="col-5 mono">{{ data_get($dashboard, 'kpis7d.transactionsCount', '—') }}</dd>

                    <dt class="col-7 text-muted">Volume</dt>
                    <dd class="col-5 mono">{{ data_get($dashboard, 'kpis7d.volume', '—') }}</dd>

                    <dt class="col-7 text-muted">Fees</dt>
                    <dd class="col-5 mono">{{ data_get($dashboard, 'kpis7d.fees', '—') }}</dd>
                </dl>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Terminals</h6>
                <dl class="row mb-0" style="font-size:.92rem;">
                    <dt class="col-7 text-muted">Total</dt>
                    <dd class="col-5 mono">{{ data_get($dashboard, 'terminalsSummary.total', '—') }}</dd>

                    <dt class="col-7 text-muted">Active</dt>
                    <dd class="col-5 mono">{{ data_get($dashboard, 'terminalsSummary.active', '—') }}</dd>

                    <dt class="col-7 text-muted">Suspended</dt>
                    <dd class="col-5 mono">{{ data_get($dashboard, 'terminalsSummary.suspended', '—') }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
