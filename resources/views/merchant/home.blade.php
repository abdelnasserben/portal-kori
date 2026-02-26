@extends('layouts.app')

@section('content')
    <x-page-header title="Merchant Dashboard" subtitle="Operational view" :breadcrumbs="[['label' => 'Dashboard']]">
        <x-slot:actions>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.terminals') }}">Terminals</a>
            <a class="btn btn-sm btn-primary" href="{{ route('merchant.me.transactions') }}">Transactions</a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <h6 class="fw-semibold mb-3">Profile</h6>
                <x-key-value-list :items="[
                    'Code' => data_get($dashboard, 'profile.code', '—'),
                    'Name' => data_get($dashboard, 'profile.displayName', '—'),
                    'Status' => view('components.status-badge', [
                        'value' => data_get($dashboard, 'profile.status', 'UNKNOWN'),
                    ])->render(),
                ]" />
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <h6 class="fw-semibold mb-3">Last 7 days</h6>
                <x-key-value-list :items="[
                    'Transactions' => data_get($dashboard, 'kpis7d.transactionsCount'),
                    'Volume' => data_get($dashboard, 'kpis7d.volume'),
                    'Fees' => data_get($dashboard, 'kpis7d.fees'),
                ]" />
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <h6 class="fw-semibold mb-3">Terminals summary</h6>
                <x-key-value-list :items="[
                    'Total' => data_get($dashboard, 'terminalsSummary.total'),
                    'Active' => data_get($dashboard, 'terminalsSummary.active', '—'),
                    'Suspended' => data_get($dashboard, 'terminalsSummary.suspended', '—'),
                ]" />
            </div>
        </div>
    </div>

    <div class="panel mb-3">
        <h6 class="fw-semibold mb-3">Balance</h6>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (data_get($dashboard, 'balance.balances', []) as $balance)
                        <tr>
                            <td>{{ data_get($balance, 'kind', '—') }}</td>
                            <td class="mono">{{ number_format((float) data_get($balance, 'amount', 0), 2) }}
                                {{ data_get($dashboard, 'balance.currency', '') }}</td>
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

    <div class="panel">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-semibold mb-0">Recent transactions</h6>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.transactions') }}">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Created at</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Amount</th>
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
                            <td class="mono">{{ data_get($it, 'amount', '—') }} {{ data_get($it, 'currency', '') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"><x-empty-state title="No recent transactions" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
