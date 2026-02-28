@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success py-2">{{ session('status_success') }}</div>
    @endif

    <div class="card panel p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-semibold mb-0">Transaction details</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.index') }}">Back to list</a>
        </div>

        <dl class="row mb-0">
            <dt class="col-sm-3">Transaction Ref</dt>
            <dd class="col-sm-9 mono">{{ $item['transactionRef'] ?? '—' }}</dd>

            <dt class="col-sm-3">Type</dt>
            <dd class="col-sm-9"><span class="badge text-bg-secondary">{{ $item['type'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['status'] ?? '—' }}</span></dd>

            <dt class="col-sm-3">Amount</dt>
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

            <dt class="col-sm-3">Created at</dt>
            <dd class="col-sm-9">@dateIso($item['createdAt'] ?? null, '—')</dd>
        </dl>
    </div>

    @if (!empty($item['payout']))
        <div class="card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h6 class="fw-semibold mb-0">Linked payout</h6>
                @if (!empty($item['payout']['payoutRef']) && ($item['payout']['status'] ?? null) === 'REQUESTED')
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.payouts.complete', $item['payout']['payoutRef']) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-success" type="submit">
                                Mark complete
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.payouts.fail', $item['payout']['payoutRef']) }}"
                            class="d-flex gap-2">
                            @csrf
                            <input class="form-control form-control-sm" name="reason" maxlength="255" required
                                placeholder="Failure reason">
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                Mark failed
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <dl class="row mb-0">
                <dt class="col-sm-3">Payout Ref</dt>
                <dd class="col-sm-9 mono">{{ $item['payout']['payoutRef'] ?? '—' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['payout']['status'] ?? '—' }}</span></dd>

                <dt class="col-sm-3">Amount</dt>
                <dd class="col-sm-9 mono">{{ $item['payout']['amount'] ?? '—' }} {{ $item['currency'] ?? '' }}</dd>

                <dt class="col-sm-3">Created at</dt>
                <dd class="col-sm-9">@dateIso($item['payout']['createdAt'] ?? null, '—')</dd>

                <dt class="col-sm-3">Completed at</dt>
                <dd class="col-sm-9">@dateIso($item['payout']['completedAt'] ?? null, '—')</dd>

                <dt class="col-sm-3">Failed at</dt>
                <dd class="col-sm-9">@dateIso($item['payout']['failedAt'] ?? null, '—')</dd>

                <dt class="col-sm-3">Failure reason</dt>
                <dd class="col-sm-9">{{ $item['payout']['failureReason'] ?? '—' }}</dd>
            </dl>
        </div>
    @endif

    @if (!empty($item['clientRefund']))
        <div class="card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h6 class="fw-semibold mb-0">Linked client refund</h6>
                @if (!empty($item['clientRefund']['refundRef']) && ($item['clientRefund']['status'] ?? null) === 'REQUESTED')
                    <div class="d-flex gap-2">
                        <form method="POST"
                            action="{{ route('admin.client-refunds.complete', $item['clientRefund']['refundRef']) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-success" type="submit">
                                Mark complete
                            </button>
                        </form>

                        <form method="POST"
                            action="{{ route('admin.client-refunds.fail', $item['clientRefund']['refundRef']) }}"
                            class="d-flex gap-2">
                            @csrf
                            <input class="form-control form-control-sm" name="reason" maxlength="255" required
                                placeholder="Failure reason">
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                Mark failed
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <dl class="row mb-0">
                <dt class="col-sm-3">Refund Ref</dt>
                <dd class="col-sm-9 mono">{{ $item['clientRefund']['refundRef'] ?? '—' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge text-bg-light">{{ $item['clientRefund']['status'] ?? '—' }}</span>
                </dd>

                <dt class="col-sm-3">Amount</dt>
                <dd class="col-sm-9 mono">{{ $item['clientRefund']['amount'] ?? '—' }} {{ $item['currency'] ?? '' }}</dd>

                <dt class="col-sm-3">Created at</dt>
                <dd class="col-sm-9">@dateIso($item['clientRefund']['createdAt'] ?? null, '—')</dd>

                <dt class="col-sm-3">Completed at</dt>
                <dd class="col-sm-9">@dateIso($item['clientRefund']['completedAt'] ?? null, '—')</dd>

                <dt class="col-sm-3">Failed at</dt>
                <dd class="col-sm-9">@dateIso($item['clientRefund']['failedAt'] ?? null, '—')</dd>

                <dt class="col-sm-3">Failure reason</dt>
                <dd class="col-sm-9">{{ $item['clientRefund']['failureReason'] ?? '—' }}</dd>
            </dl>
        </div>
    @endif

    <div class="card p-0">
        <div class="card-header bg-light fw-semibold">Ledger lines</div>
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
                            <td colspan="5" class="text-center text-muted p-4">No ledger entries.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
