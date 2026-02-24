<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\AdminStatusUpdateRequest;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Auth\JwtDecoder;
use App\Services\Backoffice\AdminsService;
use App\Services\Backoffice\AuditEventsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminsController extends Controller
{

    public function __construct(
        private readonly AdminsService $service,
        private readonly AuditEventsService $auditEvents,
        private readonly JwtDecoder $decoder,
    ) {}

    public function index(ListFiltersRequest $request)
    {
        $filters = $request->validatedWithDefaults();

        $data = $this->service->list($filters);

        return view('backoffice.admins.index', [
            'filters' => $filters,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
            'currentAdminUsername' => $this->currentAdminUsername(),
        ]);
    }

    public function create()
    {
        return view('backoffice.admins.create');
    }

    public function show(string $adminUsername)
    {
        $item = $this->service->show(
            adminUsername: $adminUsername,
            correlationId: (string) Str::uuid(),
        );

        $auditEvents = $this->auditEvents->list([
            'actorType' => 'ADMIN',
            'actorRef' => $item['actorRef'] ?? $adminUsername,
            'limit' => 10,
            'sort' => 'occurredAt:desc',
        ]);

        return view('backoffice.admins.show', [
            'item' => $item,
            'auditEvents' => $auditEvents['items'] ?? [],
            'historyRoute' => route('admin.audits.index', ['actorType' => 'ADMIN', 'actorRef' => $item['actorRef'] ?? $adminUsername]),
            'currentAdminUsername' => $this->currentAdminUsername(),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'username' => ['required', 'string', 'regex:/^[A-Za-z0-9._@-]{3,64}$/'],
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->create(
            username: $payload['username'],
            displayName: $payload['displayName'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.admins.created', [
            'created' => $created,
            'meta' => [
                'username'       => $payload['username'],
                'displayName'    => $payload['displayName'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }

    public function updateStatus(AdminStatusUpdateRequest $request)
    {
        $payload = $request->validated();

        if ($this->isCurrentAdmin($payload['adminUsername'])) {
            throw ValidationException::withMessages([
                'adminUsername' => 'Vous ne pouvez pas modifier votre propre statut.',
            ]);
        }

        $this->service->updateStatus(
            adminUsername: $payload['adminUsername'],
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut admin %s mis à jour vers %s.',
            $payload['adminUsername'],
            $payload['targetStatus']
        ));
    }

    // Is not the username of Keycloak but the actorRef
    private function currentAdminUsername(): ?string
    {
        $payload = $this->decoder->decodePayload(session('access_token'));

        if (!is_array($payload)) {
            return null;
        }

        $value = $payload['actorRef'] ?? null;

        if (is_string($value) && ($value = trim($value)) !== '') {
            return $value;
        }

        return null;
    }

    private function isCurrentAdmin(string $adminUsername): bool
    {
        $current = $this->currentAdminUsername();

        return is_string($current) && strcasecmp($current, $adminUsername) === 0;
    }
}
