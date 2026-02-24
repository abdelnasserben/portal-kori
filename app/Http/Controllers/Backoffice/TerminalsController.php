<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Backoffice\Actors\AbstractActorController;
use App\Http\Requests\Backoffice\TerminalStatusUpdateRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\TerminalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TerminalsController extends AbstractActorController
{

    public function __construct(
        private readonly TerminalsService $terminalsService,
        AuditEventsService $auditEvents,
    ) {
        parent::__construct($terminalsService, $auditEvents);
    }

    public function create(Request $request)
    {
        $query = $request->validate([
            'merchantCode' => ['nullable', 'string', 'max:16'],
        ]);

        return view('backoffice.terminals.create', [
            'merchantCode' => $query['merchantCode'] ?? '',
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'merchantCode' => ['required', 'string', 'max:16'],
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->terminalsService->create(
            merchantCode: $payload['merchantCode'],
            displayName: $payload['displayName'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.terminals.created', [
            'created' => $created,
            'meta' => [
                'merchantCode'   => $payload['merchantCode'],
                'displayName'    => $payload['displayName'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }

    public function updateStatus(TerminalStatusUpdateRequest $request)
    {
        $payload = $request->validated();
        return $this->updateActorStatus($payload['terminalUid'], $payload, 'Statut terminal %s mis à jour vers %s.');
    }

    protected function actorType(): string
    {
        return 'TERMINAL';
    }

    protected function indexView(): string
    {
        return 'backoffice.terminals.index';
    }

    protected function showView(): string
    {
        return 'backoffice.terminals.show';
    }
}
