<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class TerminalsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function list(array $filters): array
    {
        $query = array_filter($filters, fn ($v) => !is_null($v) && $v !== '');

        return $this->api->get('/api/v1/backoffice/terminals', $query);
    }

    public function create(string $merchantCode, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post('/api/v1/terminals', [
            'merchantCode' => $merchantCode,
        ], $headers);
    }

    public function updateStatus(string $terminalUid, string $targetStatus, ?string $reason = null, ?string $correlationId = null): array
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

        return $this->api->patch("/api/v1/terminals/{$terminalUid}/status", $payload, $headers);
    }
}
