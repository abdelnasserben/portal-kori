<?php

namespace App\DTO\Backoffice;

final readonly class ActorStatusUpdate
{
    public function __construct(
        public string $targetStatus,
        public ?string $reason = null,
        public ?string $correlationId = null,
    ) {}

    public function toPayload(): array
    {
        $payload = ['targetStatus' => $this->targetStatus];

        if (!is_null($this->reason) && $this->reason !== '') {
            $payload['reason'] = $this->reason;
        }

        return $payload;
    }
}
