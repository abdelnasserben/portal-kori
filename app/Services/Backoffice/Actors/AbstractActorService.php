<?php

namespace App\Services\Backoffice\Actors;

use App\DTO\Backoffice\ActorStatusUpdate;
use App\DTO\Backoffice\ActorSummary;
use App\DTO\Backoffice\ListFilters;
use App\DTO\Backoffice\PageCursor;
use App\DTO\Backoffice\PagedResult;
use App\Services\KoriApiClient;

abstract class AbstractActorService implements ActorServiceContract
{
    public function __construct(protected readonly KoriApiClient $api) {}

    public function list(ListFilters $filters): PagedResult
    {
        $response = $this->api->get($this->listEndpoint(), $filters->toQuery());
        $items = array_map(
            static fn (array $item): ActorSummary => ActorSummary::fromArray($item),
            array_values($response['items'] ?? []),
        );

        return new PagedResult(
            items: $items,
            page: PageCursor::fromArray($response['page'] ?? ['hasMore' => false]),
        );
    }

    public function show(string $actorCode, ?string $correlationId = null): ActorSummary
    {
        return ActorSummary::fromArray($this->api->get(
            "/api/v1/backoffice/actors/{$this->actorType()}/{$actorCode}",
            [],
            $this->correlationHeaders($correlationId),
        ));
    }

    public function updateStatus(string $actorCode, ActorStatusUpdate $statusUpdate): array
    {
        return $this->api->patch(
            $this->statusEndpoint($actorCode),
            $statusUpdate->toPayload(),
            $this->correlationHeaders($statusUpdate->correlationId),
        );
    }

    protected function correlationHeaders(?string $correlationId): array
    {
        return $correlationId ? ['X-Correlation-Id' => $correlationId] : [];
    }

    abstract protected function listEndpoint(): string;

    abstract protected function statusEndpoint(string $actorCode): string;

    abstract protected function actorType(): string;
}
