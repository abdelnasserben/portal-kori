<?php

namespace App\Services\Backoffice;

use App\Services\Backoffice\Actors\AbstractActorService;

class TerminalsService extends AbstractActorService
{

    public function create(string $merchantCode, string $displayName, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = ['Idempotency-Key' => $idempotencyKey] + $this->correlationHeaders($correlationId);

        return $this->api->post('/api/v1/terminals', [
            'merchantCode' => $merchantCode,
            'displayName' => $displayName,
        ], $headers);
    }

    protected function listEndpoint(): string
    {
        return '/api/v1/backoffice/terminals';
    }

    protected function statusEndpoint(string $actorCode): string
    {
        return "/api/v1/terminals/{$actorCode}/status";
    }

    protected function actorType(): string
    {
        return 'TERMINAL';
    }
}
