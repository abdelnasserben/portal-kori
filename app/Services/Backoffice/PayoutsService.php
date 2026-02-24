<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class PayoutsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function requestAgentPayout(string $agentCode, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post('/api/v1/payouts/requests', [
            'agentCode' => $agentCode,
        ], $headers);
    }

    public function complete(string $payoutId, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post("/api/v1/payouts/{$payoutId}/complete", [], $headers);
    }

    public function fail(string $payoutId, string $reason, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post("/api/v1/payouts/{$payoutId}/fail", [
            'reason' => $reason,
        ], $headers);
    }
}
