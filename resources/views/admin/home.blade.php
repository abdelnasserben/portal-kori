@extends('layouts.app')

@section('content')
    @php
        $kpisToday = data_get($dashboard ?? [], 'kpisToday', []);
        $kpis7d = data_get($dashboard ?? [], 'kpis7d', []);
        $queues = data_get($dashboard ?? [], 'queues', []);
        $platformFunds = data_get($dashboard ?? [], 'platformFunds', []);
        $fundAccounts = data_get($platformFunds, 'accounts', []);
        $recentAuditEvents = data_get($dashboard ?? [], 'recentAuditEvents', []);

        $todayStatuses = collect(data_get($kpisToday, 'byStatus', []))->sortKeys();
        $weeklyStatuses = collect(data_get($kpis7d, 'byStatus', []))->sortKeys();

        $queueCards = [
            [
                'label' => 'Payout requests',
                'value' => data_get($queues, 'agentPayoutRequestedCount', 0),
                'icon' => '💸',
            ],
            [
                'label' => 'Refund requests',
                'value' => data_get($queues, 'clientRefundRequestedCount', 0),
                'icon' => '🧾',
            ],
        ];
    @endphp

    <section class="dashboard-hero mb-4">
        <div>
            <p class="dashboard-eyebrow mb-2">Backoffice Command Center</p>
            <h1 class="h3 mb-1">Admin Dashboard</h1>
            <p class="text-secondary mb-0">Backoffice overview.</p>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card dashboard-kpi-card h-100 p-3 p-lg-4">
                <p class="dashboard-kpi-title mb-3">Today</p>
                <div class="row g-3">
                    <div class="col-4">
                        <div class="dashboard-kpi-metric">
                            {{ number_format((int) data_get($kpisToday, 'txCount', 0), 0, ',', ' ') }}</div>
                        <div class="text-secondary small">Transactions</div>
                    </div>
                    <div class="col-8">
                        <div class="dashboard-kpi-metric">
                            {{ number_format((float) data_get($kpisToday, 'txVolume', 0), 2, ',', ' ') }} KMF</div>
                        <div class="text-secondary small">Volume</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card dashboard-kpi-card h-100 p-3 p-lg-4">
                <p class="dashboard-kpi-title mb-3">Last 7 days</p>
                <div class="row g-3">
                    <div class="col-4">
                        <div class="dashboard-kpi-metric">
                            {{ number_format((int) data_get($kpis7d, 'txCount', 0), 0, ',', ' ') }}</div>
                        <div class="text-secondary small">Transactions</div>
                    </div>
                    <div class="col-8">
                        <div class="dashboard-kpi-metric">
                            {{ number_format((float) data_get($kpis7d, 'txVolume', 0), 2, ',', ' ') }} KMF</div>
                        <div class="text-secondary small">Volume</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card border-0 h-100 panel">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h6 mb-0">Status (Today)</h2>
                    <span class="text-secondary small">By status</span>
                </div>
                @if ($todayStatuses->isEmpty())
                    <x-empty-state title="No data" />
                @else
                    <ul class="list-group list-group-flush dashboard-status-list">
                        @foreach ($todayStatuses as $status => $count)
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
            <div class="card border-0 h-100 panel">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h6 mb-0">Status (7 days)</h2>
                    <span class="text-secondary small">By status</span>
                </div>
                @if ($weeklyStatuses->isEmpty())
                    <x-empty-state title="No data" />
                @else
                    <ul class="list-group list-group-flush dashboard-status-list">
                        @foreach ($weeklyStatuses as $status => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <x-status-badge :value="$status" />
                                <span class="fw-semibold">{{ number_format((int) $count, 0, ',', ' ') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-4 panel">
            <h2 class="h6 mb-3">Queues</h2>
            <div class="d-grid gap-2">
                @foreach ($queueCards as $queue)
                    <div class="dashboard-pill d-flex justify-content-between align-items-center">
                        <span>{{ $queue['icon'] }} {{ $queue['label'] }}</span>
                        <span class="fw-semibold">{{ number_format((int) $queue['value'], 0, ',', ' ') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="card p-3 h-100 panel border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 mb-0">Platform funds</h2>
                    <span class="small text-secondary">{{ data_get($platformFunds, 'currency', 'N/A') }}</span>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-4">
                        <div class="dashboard-fund-stat">
                            <div class="small text-secondary">Net position</div>
                            <div class="fw-semibold fs-5">
                                {{ number_format((float) data_get($platformFunds, 'netPosition', 0), 2, ',', ' ') }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="dashboard-fund-stat h-100">
                            <div class="small text-secondary mb-1">Accounts</div>
                            @if (empty($fundAccounts))
                                <div class="text-secondary small">No account</div>
                            @else
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($fundAccounts as $account)
                                        <span class="dashboard-account-chip">
                                            {{ data_get($account, 'accountType', 'UNKNOWN') }}:
                                            {{ number_format((float) data_get($account, 'balance', 0), 2, ',', ' ') }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-semibold mb-0">Recent events</h6>
            <a href="{{ route('admin.audits.index') }}" class="btn btn-sm btn-outline-primary">All audits</a>
        </div>

        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Occurred at</th>
                        <th>Action</th>
                        <th>Actor</th>
                        <th>Resource</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (data_get($dashboard, 'recentAuditEvents', []) as $event)
                        <tr>
                            <td>@dateIso(data_get($event, 'occurredAt'))</td>
                            <td><x-status-badge :value="data_get($event, 'action', '—')" /></td>
                            <td class="mono">{{ data_get($event, 'actorRef', '—') }}</td>
                            <td>{{ data_get($event, 'resourceType', '—') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-empty-state title="No recent audit events" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
