<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\ClientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientsController extends Controller
{
    private const ALLOWED_STATUSES = ['ACTIVE', 'SUSPENDED', 'CLOSED'];

    public function __construct(
        private readonly ClientsService $service,
        private readonly AuditEventsService $auditEvents,
    ) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'query'       => ['nullable', 'string', 'max:120'],
            'status'      => ['nullable', 'string', 'max:50'],
            'createdFrom' => ['nullable', 'string', 'max:50'],
            'createdTo'   => ['nullable', 'string', 'max:50'],
            'limit'       => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'      => ['nullable', 'string', 'max:500'],
            'sort'        => ['nullable', 'string', 'max:50'],
        ]);

        $filters['limit'] = $filters['limit'] ?? 25;

        $data = $this->service->list($filters);

        return view('backoffice.clients.index', [
            'filters' => $filters,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
        ]);
    }

    public function show(string $clientCode)
    {
        $item = $this->service->show(
            clientCode: $clientCode,
            correlationId: (string) Str::uuid(),
        );

        $auditEvents = $this->auditEvents->list([
            'actorType' => 'CLIENT',
            'actorRef' => $item['actorRef'] ?? $clientCode,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        return view('backoffice.clients.show', [
            'item' => $item,
            'auditEvents' => $auditEvents['items'] ?? [],
            'historyRoute' => route('admin.audits.index', ['actorType' => 'CLIENT', 'actorRef' => $item['actorRef'] ?? $clientCode]),
        ]);
    }

    public function updateStatus(Request $request, string $clientCode)
    {
        $payload = $request->validate([
            'targetStatus' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->service->updateStatus(
            clientCode: $clientCode,
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut client %s mis à jour vers %s.',
            $clientCode,
            $payload['targetStatus']
        ));
    }
}
