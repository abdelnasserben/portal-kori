<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class ConfigurationsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function getFees(?string $correlationId = null): array
    {
        return $this->api->get('/api/v1/config/fees', [], $this->correlationHeaders($correlationId));
    }

    public function updateFees(array $payload, ?string $correlationId = null): array
    {
        return $this->api->patch('/api/v1/config/fees', $payload, $this->correlationHeaders($correlationId));
    }

    public function getCommissions(?string $correlationId = null): array
    {
        return $this->api->get('/api/v1/config/commissions', [], $this->correlationHeaders($correlationId));
    }

    public function updateCommissions(array $payload, ?string $correlationId = null): array
    {
        return $this->api->patch('/api/v1/config/commissions', $payload, $this->correlationHeaders($correlationId));
    }

    public function getPlatform(?string $correlationId = null): array
    {
        return $this->api->get('/api/v1/config/platform', [], $this->correlationHeaders($correlationId));
    }

    public function updatePlatform(array $payload, ?string $correlationId = null): array
    {
        return $this->api->patch('/api/v1/config/platform', $payload, $this->correlationHeaders($correlationId));
    }

    private function correlationHeaders(?string $correlationId): array
    {
        if (!$correlationId) {
            return [];
        }

        return ['X-Correlation-Id' => $correlationId];
    }
}
