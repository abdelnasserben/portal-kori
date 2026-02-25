@extends('layouts.app')

@section('content')
    <x-page-header title="Terminal details" subtitle="Merchant terminal record" :back-href="route('merchant.me.terminals')"
        back-label="Back to terminals" />

    <div class="panel">
        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted">Terminal UID</dt>
            <dd class="col-sm-8 mono">{{ $item['terminalUid'] ?? '—' }}</dd>

            <dt class="col-sm-4 text-muted">Status</dt>
            <dd class="col-sm-8"><x-status-badge :value="$item['status'] ?? ''" /></dd>

            <dt class="col-sm-4 text-muted">Created at</dt>
            <dd class="col-sm-8">@dateIso($item['createdAt'] ?? null)</dd>

            <dt class="col-sm-4 text-muted">Last seen</dt>
            <dd class="col-sm-8">@dateIso($item['lastSeen'] ?? null)</dd>

            <dt class="col-sm-4 text-muted">Merchant code</dt>
            <dd class="col-sm-8 mono">{{ $item['merchantCode'] ?? '—' }}</dd>
        </dl>
    </div>
@endsection
