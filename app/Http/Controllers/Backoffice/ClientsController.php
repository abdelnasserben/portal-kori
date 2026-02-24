<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Backoffice\Actors\AbstractActorController;
use App\Http\Requests\Backoffice\ActorStatusUpdateRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\ClientsService;

class ClientsController extends AbstractActorController
{

    public function __construct(
        ClientsService $clientsService,
        AuditEventsService $auditEvents,
    ) {
        parent::__construct($clientsService, $auditEvents);
    }

    public function updateStatus(ActorStatusUpdateRequest $request, string $clientCode)
    {
        return $this->updateActorStatus($clientCode, $request->validated(), 'Statut client %s mis à jour vers %s.');
    }

    protected function actorType(): string
    {
        return 'CLIENT';
    }

    protected function indexView(): string
    {
        return 'backoffice.clients.index';
    }

    protected function showView(): string
    {
        return 'backoffice.clients.show';
    }
}
