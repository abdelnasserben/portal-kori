<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class AccountProfilesService
{
    public function __construct(private readonly KoriApiClient $api) {}

    public function updateStatus(string $accountType, string $ownerRef, string $targetStatus, ?string $reason = null, ?string $correlationId = null): array
    {
        $headers = [];

        if ($correlationId) {
            $headers['X-Correlation-Id'] = $correlationId;
        }

        $payload = [
            'accountType' => $accountType,
            'ownerRef' => $ownerRef,
            'targetStatus' => $targetStatus,
        ];

        if (!is_null($reason) && $reason !== '') {
            $payload['reason'] = $reason;
        }

        return $this->api->patch('/api/v1/account-profiles/status', $payload, $headers);
    }
}
