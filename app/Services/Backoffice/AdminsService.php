<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class AdminsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function create(string $username, string $idempotencyKey, ?string $correlationId = null): array
    {
        $headers = [
            'Idempotency-Key' => $idempotencyKey,
        ];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->post('/api/v1/admins', [
            'username' => $username,
        ], $headers);
    }
}
