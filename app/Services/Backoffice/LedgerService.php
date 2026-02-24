<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class LedgerService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function getBalance(string $accountType, string $ownerRef): array
    {
        return $this->api->get('/api/v1/ledger/balance', [
            'accountType' => $accountType,
            'ownerRef' => $ownerRef,
        ]);
    }

    public function searchTransactions(array $filters): array
    {
        $payload = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

        return $this->api->post('/api/v1/ledger/transactions/search', $payload);
    }
}
