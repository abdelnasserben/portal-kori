@extends('layouts.app')

@section('content')
    <x-page-header title="Transactions" subtitle="Backoffice transaction monitoring" :back-href="route('admin.home')"
        back-label="Back to backoffice" />

    <x-filters-bar>
        <form method="GET" action="{{ route('admin.transactions.index') }}">
            <div class="row g-2">
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
                    <label class="form-label mb-1">Terminal UID</label>
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

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Apply filters</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.index') }}">Clear</a>
                </div>
            </div>
        </form>
    </x-filters-bar>

    <x-data-table>
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Created</th>
                    <th>Transaction reference</th>
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
                        <td class="mono">
                            {{ $it['transactionRef'] ?? '' }}
                            @if (!empty($it['transactionRef']))
                                <x-copy-button :value="$it['transactionRef']" />
                            @endif
                        </td>
                        <td><x-status-badge :value="$it['type'] ?? ''" /></td>
                        <td><x-status-badge :value="$it['status'] ?? ''" /></td>
                        <td class="text-end mono">{{ number_format((float) ($it['amount'] ?? 0), 0, '.', ' ') }}</td>
                        <td>{{ $it['currency'] ?? '' }}</td>
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
                        <td colspan="7">
                            <x-empty-state title="No transactions found." message="Try adjusting your filters." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div class="text-muted small">{{ count($items) }} item(s)</div>

            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php
                        $nextUrl = route(
                            'admin.transactions.index',
                            array_merge($filters, ['cursor' => $page['nextCursor']]),
                        );
                    @endphp
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next</button>
                @endif
            </div>
        </x-slot:footer>
    </x-data-table>
@endsection
