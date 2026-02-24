@extends('layouts.app')

@section('content')
    <div class="card p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="fw-semibold mb-1">Résultats Lookup</h5>
                <div class="text-muted" style="font-size:.9rem;">Recherche globale backoffice</div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.lookups.index') }}" class="mt-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-6">
                    <label class="form-label mb-1">Recherche</label>
                    <x-form.input name="q" :value="$query" class="form-control-sm" required />
                </div>

                <div class="col-6 col-md-3">
                    <label class="form-label mb-1">Type</label>
                    <x-form.select name="type" :options="collect($types)->mapWithKeys(fn($type) => [$type => Str::before($type, '_')])->all()" :value="$selectedType" placeholder="Tous"
                        class="form-select-sm" />
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">Limit</label>
                    <x-form.input name="limit" type="number" :value="$limit" min="1" max="200" class="form-control-sm" />
                </div>

                <div class="col-12 col-md-1 d-grid">
                    <button class="btn btn-sm btn-primary" type="submit">Go</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Nom</th>
                        <th>Actor Ref</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td>
                                <span class="badge text-bg-secondary">{{ $it['entityType'] ?? 'UNKNOWN' }}</span>
                            </td>
                            <td>{{ $it['display'] ?? '—' }}</td>
                            <td class="mono">
                                {{ $it['actorRef'] ?? '—' }}
                                @if (!empty($it['actorRef']))
                                    <x-copy-button :value="$it['actorRef']" />
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-light">{{ $it['status'] ?? '—' }}</span>
                            </td>
                            <td>
                                @if (!empty($it['routeTarget']))
                                    <a class="btn btn-sm btn-outline-secondary"
                                        href="{{ route($it['routeTarget']['name'], [$it['routeTarget']['parameter'] => $it['routeTarget']['value']]) }}">Voir</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">Aucun résultat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
