<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class ClientRefundsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function request(string $clientCode, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post('/api/v1/client-refunds/requests', [
            'clientCode' => $clientCode,
        ], $headers);
    }

    public function complete(string $refundId, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post("/api/v1/client-refunds/{$refundId}/complete", [], $headers);
    }

    public function fail(string $refundId, string $reason, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post("/api/v1/client-refunds/{$refundId}/fail", [
            'reason' => $reason,
        ], $headers);
    }
}
