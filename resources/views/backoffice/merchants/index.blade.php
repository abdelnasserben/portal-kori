@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="fw-semibold mb-1">Marchands</h5>
                <div class="text-muted" style="font-size:.9rem;">Backoffice — liste paginée (cursor)</div>
            </div>

            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.terminals.create') }}">+ Créer un terminal</a>
                <a class="btn btn-sm btn-primary" href="{{ route('admin.merchants.create') }}">+ Créer un marchand</a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.merchants.index') }}" class="mt-3">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Recherche</label>
                    <input name="query" class="form-control form-control-sm" value="{{ $filters['query'] ?? '' }}"
                        placeholder="ref, code, …">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <input name="status" class="form-control form-control-sm" value="{{ $filters['status'] ?? '' }}">
                </div>

                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Created From</label>
                    <input name="createdFrom" class="form-control form-control-sm"
                        value="{{ $filters['createdFrom'] ?? '' }}" placeholder="date-time">
                </div>

                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Created To</label>
                    <input name="createdTo" class="form-control form-control-sm" value="{{ $filters['createdTo'] ?? '' }}"
                        placeholder="date-time">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Limit</label>
                    <input name="limit" type="number" class="form-control form-control-sm"
                        value="{{ $filters['limit'] ?? 25 }}" min="1" max="200">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Sort</label>
                    <input name="sort" class="form-control form-control-sm" value="{{ $filters['sort'] ?? '' }}"
                        placeholder="createdAt,desc">
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit">Filtrer</button>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.merchants.index') }}">Reset</a>
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
                        <th>Actor Ref</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td class="text-muted" style="white-space:nowrap;">{{ $it['createdAt'] ?? '' }}</td>
                            <td class="mono" style="white-space:nowrap;">
                                {{ $it['actorRef'] ?? '' }}
                                @if (!empty($it['actorRef']))
                                    <x-copy-button :value="$it['actorRef']" />
                                    <a class="btn btn-sm btn-link py-0 px-1"
                                        href="{{ route('admin.terminals.create', ['merchantCode' => $it['actorRef']]) }}">
                                        + Terminal
                                    </a>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                <span class="badge text-bg-light">{{ $it['status'] ?? '' }}</span>
                            </td>
                            <td>
                                @if (!empty($it['actorRef']))
                                    <form method="POST"
                                        action="{{ route('admin.merchants.status.update', ['merchantCode' => $it['actorRef']]) }}"
                                        class="d-flex gap-1 align-items-center">
                                        @csrf
                                        <select name="targetStatus" class="form-select form-select-sm"
                                            style="min-width:130px">
                                            @foreach (['ACTIVE', 'SUSPENDED', 'CLOSED'] as $status)
                                                <option value="{{ $status }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <input name="reason" class="form-control form-control-sm" placeholder="Reason"
                                            maxlength="255">
                                        <button class="btn btn-sm btn-outline-primary" type="submit">OK</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted p-4">Aucun marchand.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('admin.merchants.index', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next →</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
