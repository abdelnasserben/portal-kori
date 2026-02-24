<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\ActorStatusUpdateRequest;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Backoffice\AgentsService;
use App\Services\Backoffice\AuditEventsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentsController extends Controller
{

    public function __construct(
        private readonly AgentsService $service,
        private readonly AuditEventsService $auditEvents,
    ) {}


    public function index(ListFiltersRequest $request)
    {
        $filters = $request->validatedWithDefaults();

        $data = $this->service->list($filters);

        return view('backoffice.agents.index', [
            'filters' => $filters,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
        ]);
    }

    public function create()
    {
        return view('backoffice.agents.create');
    }

    public function show(string $agentCode)
    {
        $item = $this->service->show(
            agentCode: $agentCode,
            correlationId: (string) Str::uuid(),
        );

        $auditEvents = $this->auditEvents->list([
            'actorType' => 'AGENT',
            'actorRef' => $item['actorRef'] ?? $agentCode,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        return view('backoffice.agents.show', [
            'item' => $item,
            'auditEvents' => $auditEvents['items'] ?? [],
            'historyRoute' => route('admin.audits.index', ['actorType' => 'AGENT', 'actorRef' => $item['actorRef'] ?? $agentCode]),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->create(
            displayName: $payload['displayName'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.agents.created', [
            'created' => $created,
            'meta' => [
                'displayName'    => $payload['displayName'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }

    public function updateStatus(ActorStatusUpdateRequest $request, string $agentCode)
    {
        $payload = $request->validated();

        $this->service->updateStatus(
            agentCode: $agentCode,
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut agent %s mis à jour vers %s.',
            $agentCode,
            $payload['targetStatus']
        ));
    }
}
