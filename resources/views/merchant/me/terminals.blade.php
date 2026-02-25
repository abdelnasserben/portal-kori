@extends('layouts.app')

@section('content')
    <x-page-header title="Terminals" subtitle="Merchant terminal directory" :back-href="route('merchant.home')" back-label="Back to merchant home" />

    <x-filters-bar>
        <form method="GET" action="{{ route('merchant.me.terminals') }}">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Status</label>
                    <x-form.select name="status" :value="$filters['status'] ?? ''" :options="$statusOptions" placeholder="All"
                        class="form-select-sm" />
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">From</label>
                    <x-form.input name="from" type="date" :value="$filters['from'] ?? ''" class="form-control-sm" />
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">To</label>
                    <x-form.input name="to" type="date" :value="$filters['to'] ?? ''" class="form-control-sm" />
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Limit</label>
                    <x-form.input name="limit" type="number" :value="$filters['limit'] ?? 25" min="1" max="200"
                        class="form-control-sm" />
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Apply filters</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.terminals') }}">Clear</a>
                </div>
            </div>
        </form>
    </x-filters-bar>

    <x-data-table>
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Created</th>
                    <th>Terminal UID</th>
                    <th>Status</th>
                    <th>Last seen</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr>
                        <td class="text-muted">@dateIso($it['createdAt'] ?? null)</td>
                        <td class="mono">{{ $it['terminalUid'] ?? '' }}</td>
                        <td><x-status-badge :value="$it['status'] ?? ''" /></td>
                        <td class="text-muted">@dateIso($it['lastSeen'] ?? null)</td>
                        <td class="text-end">
                            @if (!empty($it['terminalUid']))
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('merchant.me.terminals.show', ['terminalUid' => $it['terminalUid']]) }}">View</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state title="No terminals found." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div class="text-muted small">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('merchant.me.terminals', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next</button>
                @endif
            </div>
        </x-slot:footer>
    </x-data-table>
@endsection
