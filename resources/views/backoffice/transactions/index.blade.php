@extends('layouts.app')

@section('content')
    <x-page-header title="Transactions" subtitle="Transaction monitoring" :breadcrumbs="[['label' => 'Dashboard', 'href' => route('admin.home')], ['label' => 'Transactions']]" />

    <x-filters-bar>
        <form method="GET" action="{{ route('admin.transactions.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Search</label>
                    <x-form.input name="query" :value="$filters['query'] ?? ''" placeholder="Reference or code" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Type</label>
                    <x-form.select name="type" :value="$filters['type'] ?? ''" :options="$transactionTypeOptions" placeholder="All"
                        class="form-select-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <x-form.select name="status" :value="$filters['status'] ?? ''" :options="$transactionStatusOptions" placeholder="All"
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
                    <label class="form-label mb-1">Minimum</label>
                    <x-form.input name="min" :value="$filters['min'] ?? ''" placeholder="1000" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Maximum</label>
                    <x-form.input name="max" :value="$filters['max'] ?? ''" placeholder="50000" class="form-control-sm" />
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Actor</label>
                    <div class="d-flex gap-2">
                        <x-form.select name="actorType" :value="$filters['actorType'] ?? ''" :options="$actorTypeOptions" placeholder="All"
                            class="form-select-sm" />
                        <x-form.input name="actorRef" :value="$filters['actorRef'] ?? ''" placeholder="actor reference"
                            class="form-control-sm" />
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Merchant</label>
                    <x-form.input name="merchantCode" :value="$filters['merchantCode'] ?? ''" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Terminal</label>
                    <x-form.input name="terminalUid" :value="$filters['terminalUid'] ?? ''" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Limit</label>
                    <x-form.input name="limit" type="number" :value="$filters['limit'] ?? 25" min="1" max="200"
                        class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Sort</label>
                    <x-form.input name="sort" :value="$filters['sort'] ?? ''" placeholder="createdAt:desc" class="form-control-sm" />
                </div>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button class="btn btn-sm btn-primary" type="submit">Apply filters</button>
                    <a class="btn btn-sm btn-dark" href="{{ route('admin.transactions.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </x-filters-bar>

    <x-data-table>
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Created</th>
                    <th>Reference</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr>
                        <td class="text-muted">@dateIso($it['createdAt'] ?? null)</td>
                        <td class="mono">
                            {{ $it['transactionRef'] ?? '' }}
                            @if (!empty($it['transactionRef']))
                                <x-copy-button :value="$it['transactionRef']" />
                            @endif
                        </td>
                        <td><x-status-badge :value="$it['type'] ?? ''" /></td>
                        <td><x-status-badge :value="$it['status'] ?? ''" /></td>
                        <td class="text-end mono">{{ number_format((float) ($it['amount'] ?? 0), 0, '.', ' ') }}
                            {{ $it['currency'] ?? 'KMF' }}</td>
                        <td class="text-end">
                            @if (!empty($it['transactionRef']))
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('admin.transactions.show', ['transactionRef' => $it['transactionRef']]) }}">View</a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>View</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <x-empty-state title="No transactions found." message="Try adjusting your filters."
                                :action-href="route('admin.transactions.index')" action-label="Clear filters" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div class="text-muted small">{{ count($items) }} item(s)</div>

            <div>
                <x-cursor-pager :has-more="$page['hasMore'] ?? false" :next-cursor="$page['nextCursor'] ?? null" route-name="admin.transactions.index"
                    :filters="$filters" />
            </div>
        </x-slot:footer>
    </x-data-table>
@endsection
