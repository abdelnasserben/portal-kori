<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Backoffice\Actors\AbstractActorController;
use App\Http\Requests\Backoffice\ActorStatusUpdateRequest;
use App\Services\Backoffice\AgentsService;
use App\Services\Backoffice\AuditEventsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentsController extends AbstractActorController
{

    public function __construct(
        private readonly AgentsService $agentsService,
        AuditEventsService $auditEvents,
    ) {
        parent::__construct($agentsService, $auditEvents);
    }

    public function create()
    {
        return view('backoffice.agents.create');
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->agentsService->create(
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
        return $this->updateActorStatus($agentCode, $request->validated(), 'Agent status %s updated to %s.');
    }

    protected function actorType(): string
    {
        return 'AGENT';
    }

    protected function indexView(): string
    {
        return 'backoffice.agents.index';
    }

    protected function showView(): string
    {
        return 'backoffice.agents.show';
    }
}
