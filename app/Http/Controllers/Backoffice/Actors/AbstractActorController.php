<?php

namespace App\Http\Controllers\Backoffice\Actors;

use App\DTO\Backoffice\ActorStatusUpdate;
use App\DTO\Backoffice\ActorSummary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Backoffice\Actors\ActorServiceContract;
use App\Services\Backoffice\AuditEventsService;
use Illuminate\Support\Str;

abstract class AbstractActorController extends Controller
{
    public function __construct(
        protected readonly ActorServiceContract $service,
        protected readonly AuditEventsService $auditEvents,
    ) {}

    public function index(ListFiltersRequest $request)
    {
        $filters = $request->toDto();
        $result = $this->service->list($filters);

        return view($this->indexView(), [
            'filters' => $filters->toArray(),
            'items' => array_map(static fn (ActorSummary $item): array => $item->toArray(), $result->items),
            'page' => $result->page->toArray(),
        ]);
    }

    public function show(string $actorCode)
    {
        $item = $this->service->show($actorCode, (string) Str::uuid());

        $actorRef = $item->actorRef !== '' ? $item->actorRef : $actorCode;
        $auditEvents = $this->auditEvents->list([
            'actorType' => $this->actorType(),
            'actorRef' => $actorRef,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        return view($this->showView(), [
            'item' => $item->toArray(),
            'auditEvents' => $auditEvents['items'] ?? [],
            'historyRoute' => route('admin.audits.index', ['actorType' => $this->actorType(), 'actorRef' => $actorRef]),
        ]);
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
