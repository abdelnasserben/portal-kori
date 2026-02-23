@extends('layouts.app')

@section('content')
    @if (
        !empty($currentAdminUsername) &&
            !empty($item['actorRef']) &&
            strcasecmp($currentAdminUsername, $item['actorRef']) === 0)
        <div class="alert alert-warning">Vous consultez votre propre compte admin. La modification de statut est désactivée.
        </div>
    @endif

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Détail admin</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.admins.index') }}">← Retour liste</a>
        </div>

        <dl class="row mb-0">
            <dt class="col-sm-3">Actor Ref</dt>
            <dd class="col-sm-9 mono">{{ $item['actorRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Display Name</dt>
            <dd class="col-sm-9">{{ $item['displayName'] ?? ($item['display'] ?? '—') }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['status'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Created At</dt>
            <dd class="col-sm-9">{{ $item['createdAt'] ?? '—' }}</dd>

            <dt class="col-sm-3">Last Activity At</dt>
            <dd class="col-sm-9">{{ $item['lastActivityAt'] ?? '—' }}</dd>
        </dl>
    </div>

    @include('backoffice.partials.actor-history', ['auditEvents' => $auditEvents ?? [], 'historyRoute' => $historyRoute ?? route('admin.audits.index')])
@endsection
