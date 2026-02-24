<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class ClientsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function list(array $filters): array
    {
        $query = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

        return $this->api->get('/api/v1/backoffice/clients', $query);
    }

    public function show(string $clientCode, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->get("/api/v1/backoffice/actors/CLIENT/{$clientCode}", [], $headers);
    }

    public function updateStatus(string $clientCode, string $targetStatus, ?string $reason = null, ?string $correlationId = null): array
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

        return $this->api->patch("/api/v1/clients/{$clientCode}/status", $payload, $headers);
    }
}
