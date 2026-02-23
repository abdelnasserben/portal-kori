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

    public function create(string $displayName, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post('/api/v1/agents', [
            'displayName' => $displayName,
        ], $headers);
    }

    public function updateStatus(string $agentCode, string $targetStatus, ?string $reason = null, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        $payload = [
            'targetStatus' => $targetStatus,
        ];

        if (!is_null($reason) && $reason !== '') {
            $payload['reason'] = $reason;
        }

        return $this->api->patch("/api/v1/agents/{$agentCode}/status", $payload, $headers);
    }
}

