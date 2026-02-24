<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\TerminalStatusUpdateRequest;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\TerminalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TerminalsController extends Controller
{

    public function __construct(
        private readonly TerminalsService $service,
        private readonly AuditEventsService $auditEvents,
    ) {}

    public function index(ListFiltersRequest $request)
    {
        $filters = $request->validatedWithDefaults();

        $data = $this->service->list($filters);

        return view('backoffice.terminals.index', [
            'filters' => $filters,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
        ]);
    }

    public function create(Request $request)
    {
        $query = $request->validate([
            'merchantCode' => ['nullable', 'string', 'max:16'],
        ]);

        return view('backoffice.terminals.create', [
            'merchantCode' => $query['merchantCode'] ?? '',
        ]);
    }

    public function show(string $terminalUid)
    {
        $item = $this->service->show(
            terminalUid: $terminalUid,
            correlationId: (string) Str::uuid(),
        );

        $auditEvents = $this->auditEvents->list([
            'actorType' => 'TERMINAL',
            'actorRef' => $item['actorRef'] ?? $terminalUid,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        return view('backoffice.terminals.show', [
            'item' => $item,
            'auditEvents' => $auditEvents['items'] ?? [],
            'historyRoute' => route('admin.audits.index', ['actorType' => 'TERMINAL', 'actorRef' => $item['actorRef'] ?? $terminalUid]),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'merchantCode' => ['required', 'string', 'max:16'],
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->create(
            merchantCode: $payload['merchantCode'],
            displayName: $payload['displayName'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.terminals.created', [
            'created' => $created,
            'meta' => [
                'merchantCode'   => $payload['merchantCode'],
                'displayName'    => $payload['displayName'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }

    public function updateStatus(TerminalStatusUpdateRequest $request)
    {
        $payload = $request->validated();
        $terminalUid = $payload['terminalUid'];

        $this->service->updateStatus(
            terminalUid: $terminalUid,
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut terminal %s mis à jour vers %s.',
            $terminalUid,
            $payload['targetStatus']
        ));
    }
}
