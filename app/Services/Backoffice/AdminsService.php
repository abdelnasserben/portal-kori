<?php

namespace App\Services\Backoffice;

use App\Services\Backoffice\Actors\AbstractActorService;

class AdminsService extends AbstractActorService
{
    public function create(string $username, string $displayName, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = ['Idempotency-Key' => $idempotencyKey] + $this->correlationHeaders($correlationId);

        return $this->api->post('/api/v1/admins', [
            'username' => $username,
            'displayName' => $displayName,
        ], $headers);
    }

    protected function listEndpoint(): string
    {
        return '/api/v1/backoffice/admins';
    }

    protected function statusEndpoint(string $actorCode): string
    {
        return "/api/v1/admins/{$actorCode}/status";
    }

    protected function actorType(): string
    {
        return 'ADMIN';
    }
}
