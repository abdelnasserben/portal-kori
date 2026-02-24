<?php

namespace App\DTO\Backoffice;

final readonly class ActorSummary
{
    public function __construct(
        public string $actorRef,
        public ?string $displayName,
        public ?string $status,
        public ?string $createdAt,
        public ?string $lastActivityAt,
        public array $raw,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            actorRef: (string) ($payload['actorRef'] ?? ''),
            displayName: self::nullableString($payload['displayName'] ?? $payload['display'] ?? null),
            status: self::nullableString($payload['status'] ?? null),
            createdAt: self::nullableString($payload['createdAt'] ?? null),
            lastActivityAt: self::nullableString($payload['lastActivityAt'] ?? null),
            raw: $payload,
        );
    }

    public function toArray(): array
    {
        return $this->raw;
    }

    private static function nullableString(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
