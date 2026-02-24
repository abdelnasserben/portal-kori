<?php

namespace App\Services\Backoffice\Actors;

use App\DTO\Backoffice\ActorStatusUpdate;
use App\DTO\Backoffice\ActorSummary;
use App\DTO\Backoffice\ListFilters;
use App\DTO\Backoffice\PagedResult;

interface ActorServiceContract
{
    public function list(ListFilters $filters): PagedResult;

    public function show(string $actorCode, ?string $correlationId = null): ActorSummary;

    public function updateStatus(string $actorCode, ActorStatusUpdate $statusUpdate): array;
}
