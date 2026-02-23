@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Détail client</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.clients.index') }}">← Retour liste</a>
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
@endsection
