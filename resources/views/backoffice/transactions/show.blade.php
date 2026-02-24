@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-semibold mb-0">Détail transaction</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.index') }}">← Retour liste</a>
        </div>

        <dl class="row mb-0">
            <dt class="col-sm-3">Transaction Ref</dt>
            <dd class="col-sm-9 mono">{{ $item['transactionRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Type</dt>
            <dd class="col-sm-9"><span class="badge text-bg-secondary">{{ $item['type'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['status'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Montant</dt>
            <dd class="col-sm-9 mono">{{ $item['amount'] ?? '—' }} {{ $item['currency'] ?? '' }}</dd>

            <dt class="col-sm-3">Merchant</dt>
            <dd class="col-sm-9 mono">{{ $item['merchantCode'] ?? '—' }}</dd>

            <dt class="col-sm-3">Agent</dt>
            <dd class="col-sm-9 mono">{{ $item['agentCode'] ?? '—' }}</dd>

            <dt class="col-sm-3">Client</dt>
            <dd class="col-sm-9 mono">{{ $item['clientCode'] ?? '—' }}</dd>

            <dt class="col-sm-3">Client Phone</dt>
            <dd class="col-sm-9 mono">{{ $item['clientPhone'] ?? '—' }}</dd>

            <dt class="col-sm-3">Terminal UID</dt>
            <dd class="col-sm-9 mono">{{ $item['terminalUid'] ?? '—' }}</dd>

            <dt class="col-sm-3">Card UID</dt>
            <dd class="col-sm-9 mono">{{ $item['cardUid'] ?? '—' }}</dd>

            <dt class="col-sm-3">Original Tx Ref</dt>
            <dd class="col-sm-9 mono">{{ $item['originalTransactionRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Créée le</dt>
            <dd class="col-sm-9">{{ $item['createdAt'] ?? '—' }}</dd>
        </dl>
    </div>

    @if (!empty($item['payout']))
        <div class="card p-4 mb-3">
            <h6 class="fw-semibold mb-3">Payout lié</h6>
            <dl class="row mb-0">
                <dt class="col-sm-3">Payout Ref</dt>
                <dd class="col-sm-9 mono">{{ $item['payout']['payoutRef'] ?? '—' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['payout']['status'] ?? '—' }}</span></dd>

                <dt class="col-sm-3">Montant</dt>
                <dd class="col-sm-9 mono">{{ $item['payout']['amount'] ?? '—' }} {{ $item['currency'] ?? '' }}</dd>

                <dt class="col-sm-3">Créé le</dt>
                <dd class="col-sm-9">{{ $item['payout']['createdAt'] ?? '—' }}</dd>

                <dt class="col-sm-3">Complété le</dt>
                <dd class="col-sm-9">{{ $item['payout']['completedAt'] ?? '—' }}</dd>

                <dt class="col-sm-3">Échec le</dt>
                <dd class="col-sm-9">{{ $item['payout']['failedAt'] ?? '—' }}</dd>

                <dt class="col-sm-3">Raison échec</dt>
                <dd class="col-sm-9">{{ $item['payout']['failureReason'] ?? '—' }}</dd>
            </dl>
        </div>
    @endif

    @if (!empty($item['clientRefund']))
        <div class="card p-4 mb-3">
            <h6 class="fw-semibold mb-3">Remboursement client lié</h6>
            <dl class="row mb-0">
                <dt class="col-sm-3">Refund Ref</dt>
                <dd class="col-sm-9 mono">{{ $item['clientRefund']['refundRef'] ?? '—' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['clientRefund']['status'] ?? '—' }}</span>
                </dd>

                <dt class="col-sm-3">Montant</dt>
                <dd class="col-sm-9 mono">{{ $item['clientRefund']['amount'] ?? '—' }} {{ $item['currency'] ?? '' }}</dd>

                <dt class="col-sm-3">Créé le</dt>
                <dd class="col-sm-9">{{ $item['clientRefund']['createdAt'] ?? '—' }}</dd>

                <dt class="col-sm-3">Complété le</dt>
                <dd class="col-sm-9">{{ $item['clientRefund']['completedAt'] ?? '—' }}</dd>

                <dt class="col-sm-3">Échec le</dt>
                <dd class="col-sm-9">{{ $item['clientRefund']['failedAt'] ?? '—' }}</dd>

                <dt class="col-sm-3">Raison échec</dt>
                <dd class="col-sm-9">{{ $item['clientRefund']['failureReason'] ?? '—' }}</dd>
            </dl>
        </div>
    @endif

    <div class="card p-0">
        <div class="card-header bg-light fw-semibold">Lignes ledger</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Account Type</th>
                        <th>Owner Ref</th>
                        <th>Entry Type</th>
                        <th class="text-end">Amount</th>
                        <th>Currency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($item['ledgerLines'] ?? []) as $line)
                        <tr>
                            <td>{{ $line['accountType'] ?? '—' }}</td>
                            <td class="mono">{{ $line['ownerRef'] ?? '—' }}</td>
                            <td>{{ $line['entryType'] ?? '—' }}</td>
                            <td class="text-end mono">{{ $line['amount'] ?? '—' }}</td>
                            <td>{{ $line['currency'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">Aucune ligne ledger.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
