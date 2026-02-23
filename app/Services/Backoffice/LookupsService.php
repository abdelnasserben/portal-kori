<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class LookupsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function search(string $query, ?string $type = null, ?int $limit = null): array
    {
        $params = [
            'q' => $query,
            'type' => $type,
            'limit' => $limit,
        ];

        $queryParams = array_filter($params, fn($value) => !is_null($value) && $value !== '');

        return $this->api->get('/api/v1/backoffice/lookups', $queryParams);
    }
}
