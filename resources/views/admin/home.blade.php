@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-3">Admin Area</h5>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('admin.transactions.index') }}">Transactions</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.payouts.create') }}">Payouts</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.client-refunds.create') }}">Client Refunds</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.ledger.index') }}">Ledger</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.audits.index') }}">Audits</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.merchants.index') }}">Merchants</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.clients.index') }}">Clients</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.cards.index') }}">Cards</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.agents.index') }}">Agents</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.terminals.index') }}">Terminals</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.admins.index') }}">Admins</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.account-profiles.index') }}">Account Profiles</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.config.index') }}">Settings</a>
        </div>
    </div>
@endsection
