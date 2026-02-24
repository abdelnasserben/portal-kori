<?php

namespace App\DTO\Backoffice;

final readonly class PageCursor
{
    public function __construct(
        public bool $hasMore,
        public ?string $nextCursor,
        public array $raw,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            hasMore: (bool) ($payload['hasMore'] ?? false),
            nextCursor: isset($payload['nextCursor']) ? (string) $payload['nextCursor'] : null,
            raw: $payload,
        );
    }

    public function toArray(): array
    {
        return $this->raw + ['hasMore' => $this->hasMore, 'nextCursor' => $this->nextCursor];
    }
}
