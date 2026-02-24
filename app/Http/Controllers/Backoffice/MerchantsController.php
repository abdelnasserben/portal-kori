<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\ActorStatusUpdateRequest;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\MerchantsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantsController extends Controller
{

    public function __construct(
        private readonly MerchantsService $service,
        private readonly AuditEventsService $auditEvents,
    ) {}

    public function index(ListFiltersRequest $request)
    {
        $filters = $request->validatedWithDefaults();

        $data = $this->service->list($filters);

        return view('backoffice.merchants.index', [
            'filters' => $filters,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
        ]);
    }

    public function create()
    {
        return view('backoffice.merchants.create');
    }

    public function show(string $merchantCode)
    {
        $item = $this->service->show(
            merchantCode: $merchantCode,
            correlationId: (string) Str::uuid(),
        );

        $auditEvents = $this->auditEvents->list([
            'actorType' => 'MERCHANT',
            'actorRef' => $item['actorRef'] ?? $merchantCode,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        return view('backoffice.merchants.show', [
            'item' => $item,
            'auditEvents' => $auditEvents['items'] ?? [],
            'historyRoute' => route('admin.audits.index', ['actorType' => 'MERCHANT', 'actorRef' => $item['actorRef'] ?? $merchantCode]),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->create(
            displayName: $payload['displayName'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.merchants.created', [
            'created' => $created,
            'meta' => [
                'displayName'    => $payload['displayName'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }

    public function updateStatus(ActorStatusUpdateRequest $request, string $merchantCode)
    {
        $payload = $request->validated();

        $this->service->updateStatus(
            merchantCode: $merchantCode,
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut marchand %s mis à jour vers %s.',
            $merchantCode,
            $payload['targetStatus']
        ));
    }
}
