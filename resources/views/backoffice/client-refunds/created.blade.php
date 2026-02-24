@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-3">Remboursement client demandé</h5>

        @if (session('status_success'))
            <div class="alert alert-success py-2">{{ session('status_success') }}</div>
        @endif

        <dl class="row mb-0">
            <dt class="col-sm-3">Client code</dt>
            <dd class="col-sm-9 mono">{{ $meta['clientCode'] ?? '—' }}</dd>

            <dt class="col-sm-3">Transaction ID</dt>
            <dd class="col-sm-9 mono">{{ $created['transactionId'] ?? '—' }}</dd>

            <dt class="col-sm-3">Refund ID</dt>
            <dd class="col-sm-9 mono">{{ $created['refundId'] ?? '—' }}</dd>

            <dt class="col-sm-3">Montant</dt>
            <dd class="col-sm-9 mono">{{ $created['amount'] ?? '—' }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><span class="badge text-bg-light">{{ $created['status'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Idempotency-Key</dt>
            <dd class="col-sm-9 mono">{{ $meta['idempotencyKey'] ?? '—' }}</dd>

            <dt class="col-sm-3">Correlation ID</dt>
            <dd class="col-sm-9 mono">{{ $meta['correlationId'] ?? '—' }}</dd>
        </dl>

        <div class="d-flex gap-2 mt-4">
            <a class="btn btn-primary" href="{{ route('admin.client-refunds.create') }}">Nouveau remboursement</a>
            @if (!empty($created['transactionId']))
                <a class="btn btn-outline-secondary" href="{{ route('admin.transactions.show', $created['transactionId']) }}">Voir transaction</a>
            @endif
        </div>
    </div>
@endsection
