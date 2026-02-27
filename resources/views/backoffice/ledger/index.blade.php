@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h5 class="fw-semibold mb-1">Ledger</h5>
                <div class="text-muted" style="font-size: .9rem;">Balance lookup and ledger history</div>
            </div>

            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">Back</a>
        </div>

        <form method="GET" action="{{ route('admin.ledger.index') }}" class="panel mt-3">
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <x-form.select name="accountType" label="Account Type" class="form-select-sm" :value="$filters['accountType'] ?? ''"
                        :options="$accountTypeOptions" placeholder="All" required />
                </div>

                <div class="col-6 col-md-3">
                    <x-form.input name="ownerRef" label="Owner" class="form-control-sm" :value="$filters['ownerRef'] ?? ''"
                        placeholder="Reference or code" required />
                </div>

                <div class="col-6 col-md-2">
                    <x-form.select name="transactionType" label="Transaction Type" class="form-select-sm" :value="$filters['transactionType'] ?? ''"
                        :options="$transactionTypeOptions" placeholder="All" />
                </div>

                <div class="col-6 col-md-2">
                    <x-form.input name="from" label="From" type="date" class="form-control-sm" :value="$filters['from'] ?? ''" />
                </div>

                <div class="col-6 col-md-2">
                    <x-form.input name="to" label="To" type="date" class="form-control-sm" :value="$filters['to'] ?? ''" />
                </div>

                <div class="col-6 col-md-1">
                    <x-form.input name="limit" label="Limit" type="number" class="form-control-sm" :value="$filters['limit'] ?? 25"
                        min="1" max="200" />
                </div>

                <div class="col-6 col-md-2">
                    <x-form.input name="minAmount" label="Min Amount" type="number" class="form-control-sm"
                        :value="$filters['minAmount'] ?? ''" placeholder="100" step="any" />
                </div>

                <div class="col-6 col-md-2">
                    <x-form.input name="maxAmount" label="Max Amount" type="number" class="form-control-sm"
                        :value="$filters['maxAmount'] ?? ''" placeholder="10000" step="any" />
                </div>

                <div class="col-6 col-md-2">
                    <x-form.select name="view" label="View" class="form-select-sm" :value="$filters['view'] ?? ''"
                        :options="$transactionViewOptions" placeholder="All" />
                </div>

                <input type="hidden" name="beforeCreatedAt" value="{{ $filters['beforeCreatedAt'] ?? '' }}">
                <input type="hidden" name="beforeTransactionId" value="{{ $filters['beforeTransactionId'] ?? '' }}">

                <div class="col-12 col-md-4 d-flex gap-2 mt-2 align-self-end">
                    <button class="btn btn-sm btn-primary" type="submit">Apply filters</button>
                    <a class="btn btn-sm btn-dark" href="{{ route('admin.ledger.index') }}">Reset</a>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td class="text-muted" style="white-space:nowrap;">@dateIso($it['createdAt'] ?? null, '—')</td>
                            <td class="mono" style="white-space:nowrap;">
                                {{ $it['transactionId'] ?? '—' }}
                                @if (!empty($it['transactionId']))
                                    <x-copy-button :value="$it['transactionId']" />
                                @endif
                            </td>
                            <td style="white-space:nowrap;"><span
                                    class="badge text-bg-secondary">{{ $it['transactionType'] ?? '—' }}</span>
                            </td>
                            <td class="text-end mono" style="white-space:nowrap;">{{ $it['amount'] ?? '—' }} KMF</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted p-4">No ledger entries.</td>
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
