<?php

namespace App\DTO\Backoffice;

final readonly class PagedResult
{
    /**
     * @param list<ActorSummary> $items
     */
    public function __construct(
        public array $items,
        public PageCursor $page,
    ) {}
}
