@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h5 class="fw-semibold mb-1">Audits</h5>
                <div class="text-muted" style="font-size: .9rem;">Audit monitoring</div>
            </div>

            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">Back</a>
        </div>

        <form method="GET" action="{{ route('admin.audits.index') }}" class="panel mt-3">
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Action</label>
                    <x-form.input name="action" :value="$filters['action'] ?? ''" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Actor</label>
                    <div class="d-flex gap-2">
                        <x-form.select name="actorType" :value="$filters['actorType'] ?? ''" :options="$actorTypeOptions" placeholder="All"
                            class="form-select-sm" />
                        <x-form.input name="actorRef" :value="$filters['actorRef'] ?? ''" placeholder="AG-000000..."
                            class="form-control-sm" />
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Resource Type</label>
                    <x-form.input name="resourceType" :value="$filters['resourceType'] ?? ''" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Resource Ref</label>
                    <x-form.input name="resourceRef" :value="$filters['resourceRef'] ?? ''" class="form-control-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Limit</label>
                    <x-form.input name="limit" type="number" :value="$filters['limit'] ?? 25" min="1" max="200"
                        class="form-control-sm" />
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
                    <label class="form-label mb-1">Sort</label>
                    <x-form.input name="sort" :value="$filters['sort'] ?? ''" placeholder="occurredAt:desc" class="form-control-sm" />
                </div>

                <div class="col-12 col-md-4 d-flex gap-2 mt-2 align-self-end">
                    <button class="btn btn-sm btn-primary" type="submit">Apply filters</button>
                    <a class="btn btn-sm btn-dark" href="{{ route('admin.audits.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="white-space:nowrap;">Occurred At</th>
                        <th style="white-space:nowrap;">Actor</th>
                        <th style="white-space:nowrap;">Action</th>
                        <th style="white-space:nowrap;">Resource</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        @php
                            $snapshot = base64_encode(
                                json_encode($it, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                            );
                        @endphp
                        <tr>
                            <td class="text-muted" style="white-space:nowrap;">@dateIso($it['occurredAt'] ?? null, '—')</td>
                            
                            <td style="white-space:nowrap;">
                                <span class="badge text-bg-secondary">{{ $it['actorType'] ?? '—' }}</span>
                                <span class="mono ms-1">{{ $it['actorRef'] ?? '—' }}</span>
                            </td>
                            <td style="white-space:nowrap;"><span
                                    class="badge text-bg-light">{{ $it['action'] ?? '—' }}</span></td>
                            <td class="mono" style="white-space:nowrap;">{{ $it['resourceType'] ?? '—' }} /
                                {{ $it['resourceRef'] ?? '—' }}</td>
                            <td>
                                @if (!empty($it['eventRef']))
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.audits.show', ['eventRef' => $it['eventRef'], 'snapshot' => $snapshot]) }}">View</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">No audits found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.9rem;">{{ count($items) }} item(s)</div>
            <div>
                @if (($page['hasMore'] ?? false) && !empty($page['nextCursor']))
                    <a class="btn btn-sm btn-outline-primary"
                        href="{{ route('admin.audits.index', array_merge($filters, ['cursor' => $page['nextCursor']])) }}">Next
                        →</a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>Next →</button>
                @endif
            </div>
        </div>
    </div>
@endsection
