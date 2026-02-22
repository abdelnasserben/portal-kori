<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class MerchantsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function list(array $filters): array
    {
        $query = array_filter($filters, fn ($v) => !is_null($v) && $v !== '');
        return $this->api->get('/api/v1/backoffice/merchants', $query);
    }

    public function create(string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];
        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        // OpenAPI: pas de requestBody pour createMerchant
        return $this->api->post('/api/v1/merchants', [], $headers);
    }

    public function updateStatus(string $merchantCode, string $targetStatus, ?string $reason = null, ?string $correlationId = null): array
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

        return $this->api->patch("/api/v1/merchants/{$merchantCode}/status", $payload, $headers);
    }
}