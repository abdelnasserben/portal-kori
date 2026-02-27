@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Admins</h5>
                <div class="text-muted" style="font-size:.9rem;">Admins directory</div>
            </div>

            <div>
                <a class="btn btn-sm btn-primary" href="{{ route('admin.admins.create') }}">Create an admin</a>
            </div>
        </div>

        @include('backoffice.partials.list-filters', [
            'routeName' => 'admin.admins.index',
            'filters' => $filters,
            'queryPlaceholder' => 'Username...',
            'statusOptions' => $actorStatusOptions,
        ])
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td>{{ $it['displayName'] ?? ($it['display'] ?? '—') }}</td>
                            <td class="mono" style="white-space:nowrap;">
                                {{ $it['actorRef'] ?? '' }}
                                @if (!empty($it['actorRef']))
                                    <x-copy-button :value="$it['actorRef']" />
                                @endif
                            </td>
                            <td><x-status-badge :value="$it['status'] ?? ''" /></td>
                            <td>
                                @if (!empty($it['actorRef']))
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.admins.show', ['adminUsername' => $it['actorRef']]) }}">View</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">No admins.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('admin.admins.index', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next →</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
