@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h5 class="fw-semibold mb-1">Transactions</h5>
                <div class="text-muted" style="font-size: .9rem;">Backoffice — liste paginée (cursor)</div>
            </div>

            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">Retour Admin</a>
        </div>

        <form method="GET" action="{{ route('admin.transactions.index') }}" class="mt-3">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Recherche</label>
                    <x-form.input name="query" :value="$filters['query'] ?? ''" placeholder="…" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Type</label>
                    <x-form.input name="type" :value="$filters['type'] ?? ''" placeholder="PAYIN…" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <x-form.input name="status" :value="$filters['status'] ?? ''" placeholder="SUCCESS…" class="form-control-sm" />
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
                    <x-form.input name="min" :value="$filters['min'] ?? ''" placeholder="1000…" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Max</label>
                    <x-form.input name="max" :value="$filters['max'] ?? ''" placeholder="50000…" class="form-control-sm" />
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Actor</label>
                    <div class="d-flex gap-2">
                        <x-form.input name="actorType" :value="$filters['actorType'] ?? ''" placeholder="admin…" class="form-control-sm" />
                        <x-form.input name="actorRef" :value="$filters['actorRef'] ?? ''" placeholder="super@admin…"
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

                {{-- Cursor est géré par les liens Next; on ne l’expose pas dans le form --}}
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Filtrer</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="white-space:nowrap;">Created</th>
                        <th style="white-space:nowrap;">Transaction Ref</th>
                        <th style="white-space:nowrap;">Type</th>
                        <th style="white-space:nowrap;">Status</th>
                        <th class="text-end" style="white-space:nowrap;">Amount</th>
                        <th style="white-space:nowrap;">Currency</th>
                        <th class="text-end" style="white-space:nowrap;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td style="white-space:nowrap;" class="text-muted">
                                {{ $it['createdAt'] ?? '' }}
                            </td>

                            <td class="mono" style="white-space:nowrap;">
                                {{ $it['transactionRef'] ?? '' }}
                                @if (!empty($it['transactionRef']))
                                    <x-copy-button :value="$it['transactionRef']" />
                                @endif
                            </td>

                            <td style="white-space:nowrap;">
                                <span class="badge text-bg-secondary">{{ $it['type'] ?? '' }}</span>
                            </td>

                            <td style="white-space:nowrap;">
                                <span class="badge text-bg-light">{{ $it['status'] ?? '' }}</span>
                            </td>

                            <td class="text-end mono" style="white-space:nowrap;">
                                {{ $it['amount'] ?? '' }}
                            </td>

                            <td style="white-space:nowrap;">
                                {{ $it['currency'] ?? '' }}
                            </td>

                            <td class="text-end" style="white-space:nowrap;">
                                @if (!empty($it['transactionRef']))
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.transactions.show', ['transactionRef' => $it['transactionRef']]) }}">Voir</a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Voir</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">
                                Aucune transaction.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">
                {{ count($items) }} item(s)
            </div>

            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php
                        $nextUrl = route(
                            'admin.transactions.index',
                            array_merge($filters, ['cursor' => $page['nextCursor']]),
                        );
                    @endphp
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">
                        Next →
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>

    {{-- JS minimal pour copier --}}
    <script>
        (function() {
            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('[data-copy-value]');
                if (!btn) return;
                const value = btn.getAttribute('data-copy-value');
                try {
                    await navigator.clipboard.writeText(value);
                    const old = btn.innerText;
                    btn.innerText = 'Copié';
                    setTimeout(() => btn.innerText = old, 900);
                } catch (err) {
                    alert('Impossible de copier');
                }
            });
        })();
    </script>
@endsection
