@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h5 class="fw-semibold mb-1">Mes terminaux</h5>
                <div class="text-muted" style="font-size: .9rem;">Endpoint: <code>/merchant/me/terminals</code></div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.home') }}">Retour</a>
        </div>

        <form method="GET" action="{{ route('merchant.me.terminals') }}" class="mt-3">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Status</label>
                    <x-form.select name="status" :value="$filters['status'] ?? ''" :options="$statusOptions" placeholder="Tous"
                        class="form-select-sm" />
                </div>
                <div class="col-6 col-md-3">
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
                    <x-form.input name="sort" :value="$filters['sort'] ?? ''" placeholder="-createdAt" class="form-control-sm" />
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Filtrer</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.terminals') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Terminal UID</th>
                        <th>Status</th>
                        <th>Créé le</th>
                        <th>Last seen</th>
                        <th>Merchant code</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td class="mono">{{ $it['terminalUid'] ?? '' }}</td>
                            <td><span class="badge text-bg-light">{{ $it['status'] ?? '' }}</span></td>
                            <td>{{ $it['createdAt'] ?? '' }}</td>
                            <td>{{ $it['lastSeen'] ?? '' }}</td>
                            <td class="mono">{{ $it['merchantCode'] ?? '' }}</td>
                            <td class="text-end">
                                @if (!empty($it['terminalUid']))
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('merchant.me.terminals.show', ['terminalUid' => $it['terminalUid']]) }}">Voir</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">Aucun terminal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('merchant.me.terminals', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next →</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
