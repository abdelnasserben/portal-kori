@extends('layouts.app')

@section('content')
    @php
        $profile = data_get($dashboard, 'profile', []);
        $balance = data_get($dashboard, 'balance', []);
        $kpis7d = data_get($dashboard, 'kpis7d', []);
        $terminalsSummary = data_get($dashboard, 'terminalsSummary', []);
        $terminalByStatus = collect(data_get($terminalsSummary, 'byStatus', []))->sortKeys();
    @endphp

    <section class="dashboard-hero mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <p class="dashboard-eyebrow mb-2">Merchant Workspace</p>
            <h1 class="h3 mb-1">Merchant Dashboard</h1>
            <p class="text-secondary mb-0">Overview of your activity and balances.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-sm btn-light" href="{{ route('merchant.me.terminals') }}">Terminals</a>
            <a class="btn btn-sm btn-primary" href="{{ route('merchant.me.transactions') }}">Transactions</a>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-4">
            <div class="card dashboard-kpi-card h-100 p-3 p-lg-4">
                <p class="dashboard-kpi-title mb-3">Profile</p>
                <div class="small text-secondary mb-1">Merchant code</div>
                <div class="dashboard-kpi-metric mono mb-3">{{ data_get($profile, 'code', '—') }}</div>
                <div class="small text-secondary mb-1">Status</div>
                <div>
                    <x-status-badge :value="data_get($profile, 'status', 'UNKNOWN')" />
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card dashboard-kpi-card h-100 p-3 p-lg-4">
                <p class="dashboard-kpi-title mb-3">Last 7 days</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="dashboard-kpi-metric">{{ number_format((int) data_get($kpis7d, 'txCount', 0), 0, ',', ' ') }}</div>
                        <div class="small text-secondary">Transactions</div>
                    </div>
                    <div class="col-6">
                        <div class="dashboard-kpi-metric">{{ number_format((int) data_get($kpis7d, 'failedCount', 0), 0, ',', ' ') }}</div>
                        <div class="small text-secondary">Failed</div>
                    </div>
                </div>
                <div class="small text-secondary mt-3 mb-1">Volume</div>
                <div class="dashboard-kpi-metric">
                    {{ number_format((float) data_get($kpis7d, 'txVolume', 0), 2, ',', ' ') }} {{ data_get($balance, 'currency', '') }}
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card dashboard-kpi-card h-100 p-3 p-lg-4">
                <p class="dashboard-kpi-title mb-3">Terminals summary</p>
                <div class="d-grid gap-2">
                    <div class="dashboard-pill d-flex justify-content-between align-items-center">
                        <span>Total terminals</span>
                        <span class="fw-semibold">{{ number_format((int) data_get($terminalsSummary, 'total', 0), 0, ',', ' ') }}</span>
                    </div>
                    <div class="dashboard-pill d-flex justify-content-between align-items-center">
                        <span>Stale terminals</span>
                        <span class="fw-semibold">{{ number_format((int) data_get($terminalsSummary, 'staleTerminals', 0), 0, ',', ' ') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h6 mb-0">Terminal status</h2>
                    <span class="text-secondary small">By status</span>
                </div>

                @if ($terminalByStatus->isEmpty())
                    <x-empty-state title="No terminal status data" />
                @else
                    <ul class="list-group list-group-flush dashboard-status-list">
                        @foreach ($terminalByStatus as $status => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <x-status-badge :value="$status" />
                                <span class="fw-semibold">{{ number_format((int) $count, 0, ',', ' ') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 mb-0">Balances</h2>
                    <span class="small text-secondary">{{ data_get($balance, 'currency', 'N/A') }}</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (data_get($balance, 'balances', []) as $row)
                                <tr>
                                    <td>{{ data_get($row, 'kind', '—') }}</td>
                                    <td class="mono text-end">{{ number_format((float) data_get($row, 'amount', 0), 2, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2"><x-empty-state title="No balance data available" /></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-semibold mb-0">Recent transactions</h6>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('merchant.me.transactions') }}">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Created at</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (data_get($dashboard, 'recentTransactions', []) as $it)
                        <tr>
                            <td>@dateIso(data_get($it, 'createdAt'))</td>
                            <td class="mono">
                                {{ data_get($it, 'transactionRef', '—') }}
                                @if (data_get($it, 'transactionRef'))
                                    <x-copy-button :value="data_get($it, 'transactionRef')" />
                                @endif
                            </td>
                            <td>{{ data_get($it, 'type', '—') }}</td>
                            <td><x-status-badge :value="data_get($it, 'status')" /></td>
                            <td class="mono text-end">{{ number_format((float) data_get($it, 'amount', 0), 2, ',', ' ') }} {{ data_get($it, 'currency', '') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"><x-empty-state title="No recent transactions" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
