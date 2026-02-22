<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class AgentsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function list(array $filters): array
    {
        $query = array_filter($filters, fn ($v) => !is_null($v) && $v !== '');
        return $this->api->get('/api/v1/backoffice/agents', $query);
    }

    public function create(string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        // OpenAPI: pas de requestBody pour createAgent
        return $this->api->post('/api/v1/agents', [], $headers);
    }
}

