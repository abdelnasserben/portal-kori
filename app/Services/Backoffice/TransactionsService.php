<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class TransactionsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    /**
     * @return array{items: array<int, array>, page: array{nextCursor?: string, hasMore?: bool}}
     */
    public function list(array $filters): array
    {
        // On ne fait AUCUNE logique métier financière ici.
        // On transmet juste les filtres/pagination à l’API.

        $query = array_filter($filters, fn ($v) => !is_null($v) && $v !== '');

        return $this->api->get('/api/v1/backoffice/transactions', $query);
    }

    public function show(string $transactionRef, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        return $this->api->get("/api/v1/backoffice/transactions/{$transactionRef}", [], $headers);
    }
}