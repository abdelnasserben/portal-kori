@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h5 class="fw-semibold mb-1">Ledger</h5>
                <div class="text-muted" style="font-size: .9rem;">Consultation balance + historique des écritures</div>
            </div>

            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">Retour Admin</a>
        </div>

        <form method="GET" action="{{ route('admin.ledger.index') }}" class="mt-3">
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Account Type *</label>
                    <input name="accountType" class="form-control form-control-sm"
                        value="{{ $filters['accountType'] ?? '' }}" placeholder="MERCHANT_CASH..." required>
                </div>

                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Owner Ref *</label>
                    <input name="ownerRef" class="form-control form-control-sm" value="{{ $filters['ownerRef'] ?? '' }}"
                        placeholder="MRC_0001..." required>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Transaction Type</label>
                    <input name="transactionType" class="form-control form-control-sm"
                        value="{{ $filters['transactionType'] ?? '' }}" placeholder="PAYIN...">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">From</label>
                    <input name="from" class="form-control form-control-sm" value="{{ $filters['from'] ?? '' }}"
                        placeholder="2026-02-01T00:00:00Z">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">To</label>
                    <input name="to" class="form-control form-control-sm" value="{{ $filters['to'] ?? '' }}"
                        placeholder="2026-02-20T23:59:59Z">
                </div>

                <div class="col-6 col-md-1">
                    <label class="form-label mb-1">Limit</label>
                    <input name="limit" type="number" class="form-control form-control-sm"
                        value="{{ $filters['limit'] ?? 25 }}" min="1" max="200">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Min Amount</label>
                    <input name="minAmount" class="form-control form-control-sm" value="{{ $filters['minAmount'] ?? '' }}"
                        placeholder="100">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Max Amount</label>
                    <input name="maxAmount" class="form-control form-control-sm" value="{{ $filters['maxAmount'] ?? '' }}"
                        placeholder="10000">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">View</label>
                    <input name="view" class="form-control form-control-sm" value="{{ $filters['view'] ?? '' }}"
                        placeholder="COMPACT...">
                </div>

                <input type="hidden" name="beforeCreatedAt" value="{{ $filters['beforeCreatedAt'] ?? '' }}">
                <input type="hidden" name="beforeTransactionId" value="{{ $filters['beforeTransactionId'] ?? '' }}">

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Rechercher</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.ledger.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    @if (!is_null($balance))
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="text-muted small">Scope</div>
                    <div>
                        <span
                            class="badge text-bg-secondary">{{ $scope['accountType'] ?? ($balance['accountType'] ?? '—') }}</span>
                        <span class="mono ms-1">{{ $scope['ownerRef'] ?? ($balance['ownerRef'] ?? '—') }}</span>
                    </div>
                </div>

                <div class="text-end">
                    <div class="text-muted small">Balance</div>
                    <div class="mono fs-5 fw-semibold">{{ $balance['balance'] ?? '—' }} {{ $balance['currency'] ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="white-space:nowrap;">Created At</th>
                        <th style="white-space:nowrap;">Transaction Ref</th>
                        <th style="white-space:nowrap;">Type</th>
                        <th class="text-end" style="white-space:nowrap;">Amount</th>
                        <th style="white-space:nowrap;">Currency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td class="text-muted" style="white-space:nowrap;">{{ $it['createdAt'] ?? '—' }}</td>
                            <td class="mono" style="white-space:nowrap;">
                                {{ $it['transactionId'] ?? '—' }}
                                @if (!empty($it['transactionId']))
                                    <x-copy-button :value="$it['transactionId']" />
                                @endif
                            </td>
                            <td style="white-space:nowrap;"><span
                                    class="badge text-bg-secondary">{{ $it['transactionType'] ?? '—' }}</span>
                            </td>
                            <td class="text-end mono" style="white-space:nowrap;">{{ $it['amount'] ?? '—' }}</td>
                            <td style="white-space:nowrap;">KMF</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">Aucune écriture ledger.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (!empty($next['beforeCreatedAt']) && !empty($next['beforeTransactionId']))
                    <a class="btn btn-sm btn-outline-primary"
                        href="{{ route('admin.ledger.index', array_merge($filters, ['beforeCreatedAt' => $next['beforeCreatedAt'], 'beforeTransactionId' => $next['beforeTransactionId']])) }}">
                        Next →
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
