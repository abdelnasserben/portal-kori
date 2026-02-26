@extends('layouts.app')

@section('content')
    <x-page-header title="Transaction details" subtitle="Merchant transaction record" :back-href="route('merchant.me.transactions')"
        back-label="Back to transactions" />

    <div class="panel">
        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted">Transaction reference</dt>
            <dd class="col-sm-8 mono">{{ $item['transactionRef'] ?? '—' }}</dd>

            <dt class="col-sm-4 text-muted">Type</dt>
            <dd class="col-sm-8"><x-status-badge :value="$item['type'] ?? ''" /></dd>

            <dt class="col-sm-4 text-muted">Status</dt>
            <dd class="col-sm-8"><x-status-badge :value="$item['status'] ?? ''" /></dd>

            <dt class="col-sm-4 text-muted">Amount</dt>
            <dd class="col-sm-8">{{ number_format((float) ($item['amount'] ?? 0), 0, '.', ' ') }} KMF</dd>

            <dt class="col-sm-4 text-muted">Fee</dt>
            <dd class="col-sm-8">{{ number_format((float) ($item['fee'] ?? 0), 0, '.', ' ') }} KMF</dd>

            <dt class="col-sm-4 text-muted">Total debited</dt>
            <dd class="col-sm-8">{{ number_format((float) ($item['totalDebited'] ?? 0), 0, '.', ' ') }} KMF</dd>

            <dt class="col-sm-4 text-muted">Agent code</dt>
            <dd class="col-sm-8 mono">{{ $item['agentCode'] ?? '—' }}</dd>

            <dt class="col-sm-4 text-muted">Client code</dt>
            <dd class="col-sm-8 mono">{{ $item['clientCode'] ?? '—' }}</dd>

            <dt class="col-sm-4 text-muted">Original transaction reference</dt>
            <dd class="col-sm-8 mono">{{ $item['originalTransactionRef'] ?? '—' }}</dd>

            <dt class="col-sm-4 text-muted">Created at</dt>
            <dd class="col-sm-8">@dateIso($item['createdAt'] ?? null)</dd>
        </dl>
    </div>
@endsection
