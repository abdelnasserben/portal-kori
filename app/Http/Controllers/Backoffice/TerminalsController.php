<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\AuditEventsService;
use App\Services\Backoffice\TerminalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TerminalsController extends Controller
{
    private const ALLOWED_STATUSES = ['ACTIVE', 'SUSPENDED', 'CLOSED'];

    public function __construct(
        private readonly TerminalsService $service,
        private readonly AuditEventsService $auditEvents,
    ) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'query'       => ['nullable', 'string', 'max:120'],
            'status'      => ['nullable', 'string', 'max:50'],
            'createdFrom' => ['nullable', 'string', 'max:50'],
            'createdTo'   => ['nullable', 'string', 'max:50'],
            'limit'       => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'      => ['nullable', 'string', 'max:500'],
            'sort'        => ['nullable', 'string', 'max:50'],
        ]);

        $filters['limit'] = $filters['limit'] ?? 25;

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

    public function updateStatus(Request $request)
    {
        $payload = $request->validate([
            'terminalUid' => ['required', 'string', 'max:120'],
            'targetStatus' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->service->updateStatus(
            terminalUid: $payload['terminalUid'],
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut terminal %s mis à jour vers %s.',
            $payload['terminalUid'],
            $payload['targetStatus']
        ));
    }
}
