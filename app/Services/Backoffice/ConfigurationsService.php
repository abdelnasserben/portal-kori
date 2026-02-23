<?php

namespace App\Services\Backoffice;

use App\Exceptions\KoriApiException;
use App\Services\KoriApiClient;

class ConfigurationsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function getFees(?string $correlationId = null): ?array
    {
        return $this->getOrNullOn404('/api/v1/config/fees', $correlationId);
    }

    public function updateFees(array $payload, ?string $correlationId = null): array
    {
        return $this->api->patch('/api/v1/config/fees', $payload, $this->correlationHeaders($correlationId));
    }

    public function getCommissions(?string $correlationId = null): ?array
    {
        return $this->getOrNullOn404('/api/v1/config/commissions', $correlationId);
    }

    public function updateCommissions(array $payload, ?string $correlationId = null): array
    {
        return $this->api->patch('/api/v1/config/commissions', $payload, $this->correlationHeaders($correlationId));
    }

    public function getPlatform(?string $correlationId = null): ?array
    {
        return $this->getOrNullOn404('/api/v1/config/platform', $correlationId);
    }

    public function updatePlatform(array $payload, ?string $correlationId = null): array
    {
        return $this->api->patch('/api/v1/config/platform', $payload, $this->correlationHeaders($correlationId));
    }

    private function getOrNullOn404(string $uri, ?string $correlationId): ?array
    {
        try {
            return $this->api->get($uri, [], $this->correlationHeaders($correlationId));
        } catch (KoriApiException $e) {
            // adapte si ton exception a un getter au lieu d’une propriété publique
            $status = $e->status ?? null;

            if ($status === 404) {
                return null;
            }

            throw $e;
        }
    }

    private function correlationHeaders(?string $correlationId): array
    {
        return $correlationId ? ['X-Correlation-Id' => $correlationId] : [];
    }
}