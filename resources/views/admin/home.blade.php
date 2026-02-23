@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-3">Espace Admin</h5>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('admin.transactions.index') }}">Transactions</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.audits.index') }}">Audits</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.merchants.index') }}">Marchands</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.clients.index') }}">Clients</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.agents.index') }}">Agents</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.terminals.index') }}">Terminaux</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.admins.index') }}">Admins</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.config.index') }}">Configuration</a>
        </div>
    </div>
@endsection
