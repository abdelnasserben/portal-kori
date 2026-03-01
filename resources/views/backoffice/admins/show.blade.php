@extends('layouts.app')

@section('content')
    @php($isCurrentAdmin = (bool) ($isCurrentAdmin ?? false))

    @if ($isCurrentAdmin)
        <div class="alert alert-warning">You are viewing your own admin account.</div>
    @endif

    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-semibold mb-0">Admin details</h5>
            <div class="d-flex gap-2">
                @unless ($isCurrentAdmin)
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal"
                        data-bs-target="#adminStatusModal">Update status</button>
                @endunless
                <a class="btn btn-sm btn-dark" href="{{ route('admin.admins.index') }}">Back to list</a>
            </div>
        </div>

        <dl class="row mb-0">
            <dt class="col-sm-3">Username</dt>
            <dd class="col-sm-9 mono">{{ $item['actorRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Name</dt>
            <dd class="col-sm-9">{{ $item['displayName'] ?? ($item['display'] ?? '—') }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><x-status-badge :value="$item['status'] ?? ''" /></dd>

            <dt class="col-sm-3">Created At</dt>
            <dd class="col-sm-9">@dateIso($item['createdAt'] ?? null, '—')</dd>

            <dt class="col-sm-3">Last Activity At</dt>
            <dd class="col-sm-9">@dateIso($item['lastActivityAt'] ?? null, '—')</dd>
        </dl>
    </div>

    @unless ($isCurrentAdmin)
        @include('backoffice.partials.actor-status-modal', [
            'modalId' => 'adminStatusModal',
            'title' => 'Edit admin status',
            'action' => route('admin.admins.status.update'),
            'statusOptions' => $actorStatusOptions,
            'hiddenFields' => ['adminUsername' => $item['actorRef'] ?? ''],
        ])
    @endunless

    @include('backoffice.partials.actor-history', [
        'auditEvents' => $auditEvents ?? [],
        'historyRoute' => $historyRoute ?? route('admin.audits.index'),
    ])
@endsection
