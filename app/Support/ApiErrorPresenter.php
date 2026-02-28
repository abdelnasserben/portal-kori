<?php

namespace App\Support;

use App\Exceptions\KoriApiException;

class ApiErrorPresenter
{
    public static function fromException(KoriApiException $e, ?string $fallbackMessage = null): array
    {
        return self::fromPayload(
            status: $e->status,
            payload: $e->payload,
            fallbackMessage: $fallbackMessage ?? $e->getMessage(),
        );
    }

    public static function fromPayload(int $status, ?array $payload = null, ?string $fallbackMessage = null): array
    {
        $payload ??= [];

        return [
            'status' => $status,
            'timestamp' => self::stringOrNull($payload, 'timestamp'),
            'correlationId' => self::stringOrNull($payload, 'correlationId')
                ?? self::stringOrNull($payload, 'correlationID'),
            'errorId' => self::stringOrNull($payload, 'errorId')
                ?? self::stringOrNull($payload, 'errorID'),
            'code' => self::stringOrNull($payload, 'code')
                ?? self::stringOrNull($payload, 'errorCode'),
            'message' => self::stringOrNull($payload, 'message')
                ?? $fallbackMessage
                ?? 'An error occurred while contacting API.',
            'details' => self::arrayOrNull($payload, 'details'),
            'path' => self::stringOrNull($payload, 'path'),
            'payload' => $payload,
        ];
    }

    private static function stringOrNull(array $payload, string $key): ?string
    {
        $value = $payload[$key] ?? null;

        return is_string($value) && trim($value) !== '' ? $value : null;
    }

    private static function arrayOrNull(array $payload, string $key): ?array
    {
        $value = $payload[$key] ?? null;

        return is_array($value) ? $value : null;
    }
}
