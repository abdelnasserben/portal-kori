@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h5 class="fw-semibold mb-1">Mes transactions</h5>
                <div class="text-muted" style="font-size: .9rem;">Endpoint: <code>/merchant/me/transactions</code></div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.home') }}">Retour</a>
        </div>

        <form method="GET" action="{{ route('merchant.me.transactions') }}" class="mt-3">
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Type</label>
                    <x-form.select name="type" :value="$filters['type'] ?? ''" :options="$transactionTypeOptions" placeholder="Tous"
                        class="form-select-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <x-form.select name="status" :value="$filters['status'] ?? ''" :options="$transactionStatusOptions" placeholder="Tous"
                        class="form-select-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">From</label>
                    <x-form.input name="from" type="date" :value="$filters['from'] ?? ''" class="form-control-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">To</label>
                    <x-form.input name="to" type="date" :value="$filters['to'] ?? ''" class="form-control-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Min</label>
                    <x-form.input name="min" :value="$filters['min'] ?? ''" class="form-control-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Max</label>
                    <x-form.input name="max" :value="$filters['max'] ?? ''" class="form-control-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Limit</label>
                    <x-form.input name="limit" type="number" :value="$filters['limit'] ?? 25" min="1" max="200"
                        class="form-control-sm" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Sort</label>
                    <x-form.input name="sort" :value="$filters['sort'] ?? ''" placeholder="-createdAt" class="form-control-sm" />
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Filtrer</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.transactions') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Created</th>
                        <th>Transaction Ref</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Amount</th>
                        <th>Currency</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td class="text-muted">@dateIso($it['createdAt'] ?? null)</td>
                            <td class="mono">{{ $it['transactionRef'] ?? '' }}</td>
                            <td><span class="badge text-bg-secondary">{{ $it['type'] ?? '' }}</span></td>
                            <td><span class="badge text-bg-light">{{ $it['status'] ?? '' }}</span></td>
                            <td class="text-end mono">{{ $it['amount'] ?? '' }}</td>
                            <td>{{ $it['currency'] ?? '' }}</td>
                            <td class="text-end">
                                @if (!empty($it['transactionRef']))
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('merchant.me.transactions.show', ['transactionRef' => $it['transactionRef']]) }}">Voir</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">Aucune transaction.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('merchant.me.transactions', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next →</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
