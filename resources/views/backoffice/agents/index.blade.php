@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <x-page-header title="Agents" subtitle="Backoffice agent directory" :back-href="route('admin.home')" back-label="Back to backoffice">
        <x-slot:actions>
            <a class="btn btn-sm btn-primary" href="{{ route('admin.agents.create') }}">Create agent</a>
        </x-slot:actions>
    </x-page-header>

    @include('backoffice.partials.list-filters', [
        'routeName' => 'admin.agents.index',
        'filters' => $filters,
        'statusOptions' => $actorStatusOptions,
        'queryPlaceholder' => 'Code',
    ])

    <x-data-table>
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Created</th>
                    <th>Agent code</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr>
                        <td class="text-muted">@dateIso($it['createdAt'] ?? null)</td>
                        <td class="mono">
                            {{ $it['actorRef'] ?? '' }}
                            @if (!empty($it['actorRef']))
                                <x-copy-button :value="$it['actorRef']" />
                            @endif
                        </td>
                        <td>{{ $it['displayName'] ?? ($it['display'] ?? '—') }}</td>
                        <td><x-status-badge :value="$it['status'] ?? ''" /></td>
                        <td>
                            @if (!empty($it['actorRef']))
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('admin.agents.show', ['agentCode' => $it['actorRef']]) }}">View</a>

                                <form method="POST" action="{{ route('admin.agents.status.update', ['agentCode' => $it['actorRef']]) }}"
                                    class="d-flex gap-1 align-items-center mt-1" data-confirm
                                    data-confirm-message="Confirm status update for this agent?">
                                    @csrf
                                    <x-form.select name="targetStatus" :options="$actorStatusOptions" class="form-select-sm"
                                        style="min-width:130px" />
                                    <x-form.input name="reason" placeholder="Reason" maxlength="255" class="form-control-sm" />
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Update</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state title="No agents found." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div class="text-muted small">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    @php($nextUrl = route('admin.agents.index', array_merge($filters, ['cursor' => $page['nextCursor']])))
                    <a class="btn btn-sm btn-outline-primary" href="{{ $nextUrl }}">Next</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next</button>
                @endif
            </div>
        </x-slot:footer>
    </x-data-table>
@endsection
