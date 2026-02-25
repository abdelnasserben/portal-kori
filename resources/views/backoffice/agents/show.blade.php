@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-semibold mb-0">Agent details</h5>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary"
                    href="{{ route('admin.payouts.create', ['agentCode' => $item['actorRef'] ?? null]) }}">Demander
                    payout</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.agents.index') }}">← Back to list</a>
            </div>
        </div>

        <dl class="row mb-0">
            <dt class="col-sm-3">Actor Ref</dt>
            <dd class="col-sm-9 mono">{{ $item['actorRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Display Name</dt>
            <dd class="col-sm-9">{{ $item['displayName'] ?? ($item['display'] ?? '—') }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['status'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Created At</dt>
            <dd class="col-sm-9">@dateIso($item['createdAt'] ?? null, '—')</dd>

            <dt class="col-sm-3">Last Activity At</dt>
            <dd class="col-sm-9">@dateIso($item['lastActivityAt'] ?? null, '—')</dd>
        </dl>
    </div>

    @include('backoffice.partials.actor-history', [
        'auditEvents' => $auditEvents ?? [],
        'historyRoute' => $historyRoute ?? route('admin.audits.index'),
    ])
@endsection
