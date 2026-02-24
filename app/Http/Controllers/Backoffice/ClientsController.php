<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\ActorStatusUpdateRequest;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\ClientsService;
use Illuminate\Support\Str;

class ClientsController extends Controller
{

    public function __construct(
        private readonly ClientsService $service,
        private readonly AuditEventsService $auditEvents,
    ) {}

    public function index(ListFiltersRequest $request)
    {
        $filters = $request->validatedWithDefaults();

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

    public function updateStatus(ActorStatusUpdateRequest $request, string $clientCode)
    {
        $payload = $request->validated();

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
