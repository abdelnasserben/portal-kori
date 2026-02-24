<?php

namespace App\Services\Backoffice;

use App\Services\Backoffice\Actors\AbstractActorService;

class ClientsService extends AbstractActorService
{
    protected function listEndpoint(): string
    {
        return '/api/v1/backoffice/clients';
    }

    protected function statusEndpoint(string $actorCode): string
    {
        return "/api/v1/clients/{$actorCode}/status";
    }

    protected function actorType(): string
    {
        return 'CLIENT';
    }
}
