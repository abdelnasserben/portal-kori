<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Backoffice\Actors\AbstractActorController;
use App\Http\Requests\Backoffice\ActorStatusUpdateRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\MerchantsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantsController extends AbstractActorController
{

    public function __construct(
        private readonly MerchantsService $merchantsService,
        AuditEventsService $auditEvents,
    ) {
        parent::__construct($merchantsService, $auditEvents);
    }

    public function create()
    {
        return view('backoffice.merchants.create');
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->merchantsService->create(
            displayName: $payload['displayName'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.merchants.created', [
            'created' => $created,
            'meta' => [
                'displayName'    => $payload['displayName'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }

    public function updateStatus(ActorStatusUpdateRequest $request, string $merchantCode)
    {
        return $this->updateActorStatus($merchantCode, $request->validated(), 'Statut marchand %s mis à jour vers %s.');
    }

    protected function actorType(): string
    {
        return 'MERCHANT';
    }

    protected function indexView(): string
    {
        return 'backoffice.merchants.index';
    }

    protected function showView(): string
    {
        return 'backoffice.merchants.show';
    }
}
