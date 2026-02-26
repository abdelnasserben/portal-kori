<?php

namespace App\Services\Merchant;

use App\Services\KoriApiClient;

class MeService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function profile(?string $correlationId = null): array
    {
        return $this->api->get('/api/v1/merchant/me/profile', [], $this->headers($correlationId));
    }

    public function dashboard(?string $correlationId = null): array
    {
        return $this->api->get('/api/v1/merchant/me/dashboard', [], $this->headers($correlationId));
    }

    public function balance(?string $correlationId = null): array
    {
        return $this->api->get('/api/v1/merchant/me/balance', [], $this->headers($correlationId));
    }

    /**
     * @return array{items: array<int, array>, page: array{nextCursor?: string, hasMore?: bool}}
     */
    public function transactions(array $filters, ?string $correlationId = null): array
    {
        $query = array_filter($filters, fn ($v) => !is_null($v) && $v !== '');

        return $this->api->get('/api/v1/merchant/me/transactions', $query, $this->headers($correlationId));
    }

    public function transaction(string $transactionRef, ?string $correlationId = null): array
    {
        return $this->api->get("/api/v1/merchant/me/transactions/{$transactionRef}", [], $this->headers($correlationId));
    }

    /**
     * @return array{items: array<int, array>, page: array{nextCursor?: string, hasMore?: bool}}
     */
    public function terminals(array $filters, ?string $correlationId = null): array
    {
        $query = array_filter($filters, fn ($v) => !is_null($v) && $v !== '');

        return $this->api->get('/api/v1/merchant/me/terminals', $query, $this->headers($correlationId));
    }

    public function terminal(string $terminalUid, ?string $correlationId = null): array
    {
        return $this->api->get("/api/v1/merchant/me/terminals/{$terminalUid}", [], $this->headers($correlationId));
    }

    private function headers(?string $correlationId = null): array
    {
        if (!$correlationId) {
            return [];
        }

        return ['X-Correlation-Id' => $correlationId];
    }
}
