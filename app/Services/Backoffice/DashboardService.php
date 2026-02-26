<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class DashboardService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function summary(?string $correlationId = null): array
    {
        $headers = $correlationId ? ['X-Correlation-Id' => $correlationId] : [];

        return $this->api->get('/api/v1/backoffice/dashboard', [], $headers);
    }
}
