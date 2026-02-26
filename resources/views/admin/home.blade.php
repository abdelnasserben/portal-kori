@extends('layouts.app')

@section('content')
    <h2 class="fw-semibold mb-4">Dashboard</h2>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">KPIs Today</h6>
                <pre class="mb-0 small">{{ json_encode(data_get($dashboard ?? [], 'kpisToday', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">KPIs 7 days</h6>
                <pre class="mb-0 small">{{ json_encode(data_get($dashboard ?? [], 'kpis7d', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Queues</h6>
                <pre class="mb-0 small">{{ json_encode(data_get($dashboard ?? [], 'queues', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 h-100">
                <h6 class="fw-semibold">Platform Funds</h6>
                <pre class="mb-0 small">{{ json_encode(data_get($dashboard ?? [], 'platformFunds', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>
@endsection
