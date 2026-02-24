<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class CardsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function updateStatus(string $cardUid, string $targetStatus, ?string $reason = null, ?string $correlationId = null): array
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

        return $this->api->patch("/api/v1/cards/{$cardUid}/status/admin", $payload, $headers);
    }

    public function unblock(string $cardUid, ?string $reason = null, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        $payload = [];

        if (!is_null($reason) && $reason !== '') {
            $payload['reason'] = $reason;
        }

        return $this->api->post("/api/v1/cards/{$cardUid}/unblock", $payload, $headers);
    }
}
