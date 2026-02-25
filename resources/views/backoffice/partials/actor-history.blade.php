<div class="card p-4 mt-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-semibold mb-0">History</h6>
        <span class="text-muted" style="font-size:.9rem;">{{ count($auditEvents ?? []) }} event(s)</span>
    </div>

    <div class="table-responsive">
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="white-space:nowrap;">Date</th>
                    <th style="white-space:nowrap;">Action</th>
                    <th style="white-space:nowrap;">Ressource</th>
                    <th style="white-space:nowrap;">Ref ressource</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($auditEvents ?? []) as $event)
                    <tr>
                        <td class="text-muted" style="white-space:nowrap;">@dateIso($event['occurredAt'] ?? null, '—')</td>
                        <td style="white-space:nowrap;"><span
                                class="badge text-bg-secondary">{{ $event['action'] ?? '—' }}</span></td>
                        <td style="white-space:nowrap;">{{ $event['resourceType'] ?? '—' }}</td>
                        <td class="mono" style="white-space:nowrap;">{{ $event['resourceRef'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted p-4">No history events for this
                            acteur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-3 d-flex justify-content-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ $historyRoute }}">View all audits</a>
    </div>
</div>
