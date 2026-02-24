<?php

namespace App\Services\Backoffice;

use App\Services\Backoffice\Actors\AbstractActorService;

class AgentsService extends AbstractActorService
{

    public function create(string $displayName, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = ['Idempotency-Key' => $idempotencyKey] + $this->correlationHeaders($correlationId);
        return $this->api->post('/api/v1/agents', ['displayName' => $displayName], $headers);
    }

    protected function listEndpoint(): string
    {
        return '/api/v1/backoffice/agents';
    }

    protected function statusEndpoint(string $actorCode): string
    {
        return "/api/v1/agents/{$actorCode}/status";
    }

    protected function actorType(): string
    {
        return 'AGENT';
    }
}

