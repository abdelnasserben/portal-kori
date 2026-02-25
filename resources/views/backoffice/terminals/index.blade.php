@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Terminals</h5>
                <div class="text-muted" style="font-size:.9rem;">Listing</div>
            </div>

            <div>
                <a class="btn btn-sm btn-primary" href="{{ route('admin.terminals.create') }}">Create a terminal</a>
            </div>
        </div>

        @include('backoffice.partials.list-filters', [
            'routeName' => 'admin.terminals.index',
            'filters' => $filters,
            'statusOptions' => $actorStatusOptions,
            'queryPlaceholder' => 'uid…',
        ])
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Created</th>
                        <th>Reference</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td class="text-muted" style="white-space:nowrap;">@dateIso($it['createdAt'] ?? null)</td>
                            <td class="mono" style="white-space:nowrap;">
                                {{ $it['actorRef'] ?? '' }}
                                @if (!empty($it['actorRef']))
                                    <x-copy-button :value="$it['actorRef']" />
                                @endif
                            </td>
                            <td>{{ $it['displayName'] ?? ($it['display'] ?? '—') }}</td>
                            <td style="white-space:nowrap;">
                                <span class="badge text-bg-light">{{ $it['status'] ?? '' }}</span>
                            </td>
                            <td>
                                @if (!empty($it['actorRef']))
                                    <a class="btn btn-sm btn-outline-secondary"
                                        href="{{ route('admin.terminals.show', ['terminalUid' => $it['actorRef']]) }}">View</a>

                                    <form method="POST" action="{{ route('admin.terminals.status.update') }}"
                                        class="d-flex gap-1 align-items-center mt-1">
                                        @csrf
                                        <input type="hidden" name="terminalUid" value="{{ $it['actorRef'] }}">
                                        <x-form.select name="targetStatus" :options="$actorStatusOptions" class="form-select-sm"
                                            style="min-width:130px" />
                                        <x-form.input name="reason" placeholder="Reason" maxlength="255"
                                            class="form-control-sm" />
                                        <button class="btn btn-sm btn-outline-primary" type="submit">OK</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">No terminals.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('admin.terminals.index', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next →</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
