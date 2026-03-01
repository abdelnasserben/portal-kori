<?php

namespace App\Http\Controllers\Backoffice\Actors;

use App\DTO\Backoffice\ActorStatusUpdate;
use App\DTO\Backoffice\ActorSummary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Backoffice\Actors\ActorServiceContract;
use App\Services\Backoffice\AuditEventsService;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Support\Str;

abstract class AbstractActorController extends Controller
{
    public function __construct(
        protected readonly ActorServiceContract $service,
        protected readonly AuditEventsService $auditEvents,
    ) {}

    public function index(ListFiltersRequest $request)
    {
        $filtersDto = $request->toDto();           // ISO pour l’API
        $filtersUi  = $request->filtersForUi();    // Y-m-d pour l’HTML

        $result = $this->service->list($filtersDto);

        return view($this->indexView(), [
            'filters' => $filtersUi, // surtout pas $filtersDto->toArray()
            'items' => array_map(static fn(ActorSummary $item): array => $item->toArray(), $result->items),
            'page' => $result->page->toArray(),
            'actorStatusOptions' => FilterEnums::options(FilterEnums::ACTOR_STATUSES),
        ]);
    }

    public function show(string $actorCode)
    {
        $item = $this->service->show($actorCode, (string) Str::uuid());

        $actorRef = $item->actorRef !== '' ? $item->actorRef : $actorCode;
        $auditEvents = $this->loadAuditHistory($this->actorType(), $actorRef);

        return view($this->showView(), [
            'item' => $item->toArray(),
            'auditEvents' => $auditEvents,
            'historyRoute' => route('admin.audits.index', ['actorType' => $this->actorType(), 'actorRef' => $actorRef]),
            'actorStatusOptions' => FilterEnums::options(FilterEnums::ACTOR_STATUSES),
        ], $this->extraShowViewData($item->toArray(), $actorRef, $actorCode));
    }

    /**
     * Override in child controllers to inject extra view data for show pages.
     */
    protected function extraShowViewData(array $item, string $actorRef, string $actorCode): array
    {
        return [];
    }

    /**
     * @return array<int, array>
     */
    protected function loadAuditHistory(string $subjectType, string $subjectRef): array
    {
        $actorEvents = $this->auditEvents->list([
            'actorType' => $subjectType,
            'actorRef' => $subjectRef,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        $resourceEvents = $this->auditEvents->list([
            'resourceType' => $subjectType,
            'resourceRef' => $subjectRef,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        $items = array_merge(
            is_array($actorEvents['items'] ?? null) ? $actorEvents['items'] : [],
            is_array($resourceEvents['items'] ?? null) ? $resourceEvents['items'] : [],
        );

        $deduplicated = [];
        $seenEventRefs = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $eventRef = $item['eventRef'] ?? null;

            if (is_string($eventRef) && $eventRef !== '') {
                if (isset($seenEventRefs[$eventRef])) {
                    continue;
                }

                $seenEventRefs[$eventRef] = true;
            }

            $deduplicated[] = $item;
        }

        usort($deduplicated, static function (array $left, array $right): int {
            $leftOccurredAt = (string) ($left['occurredAt'] ?? '');
            $rightOccurredAt = (string) ($right['occurredAt'] ?? '');

            return strcmp($rightOccurredAt, $leftOccurredAt);
        });

        return array_slice($deduplicated, 0, 10);
    }

    protected function updateActorStatus(string $actorCode, array $payload, string $successMessage)
    {
        $this->service->updateStatus($actorCode, new ActorStatusUpdate(
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        ));

        return back()->with('status_success', sprintf($successMessage, $actorCode, $payload['targetStatus']));
    }

    abstract protected function actorType(): string;

    abstract protected function indexView(): string;

    abstract protected function showView(): string;
}
