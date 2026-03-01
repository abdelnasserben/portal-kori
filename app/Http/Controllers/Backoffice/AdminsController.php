<?php

namespace App\Http\Controllers\Backoffice;

use App\DTO\Backoffice\ActorSummary;
use App\Http\Controllers\Backoffice\Actors\AbstractActorController;
use App\Http\Requests\Backoffice\AdminStatusUpdateRequest;
use App\Http\Requests\Backoffice\ListFiltersRequest;
use App\Services\Auth\JwtDecoder;
use App\Services\Backoffice\AdminsService;
use App\Services\Backoffice\AuditEventsService;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminsController extends AbstractActorController
{

    public function __construct(
        private readonly AdminsService $adminsService,
        AuditEventsService $auditEvents,
        private readonly JwtDecoder $decoder,
    ) {
        parent::__construct($adminsService, $auditEvents);
    }

    public function create()
    {
        return view('backoffice.admins.create');
    }

    public function index(ListFiltersRequest $request)
    {
        $filters = $request->toDto();
        $result = $this->adminsService->list($filters);

        return view('backoffice.admins.index', [
            'filters' => $filters->toArray(),
            'items' => array_map(static fn(ActorSummary $item): array => $item->toArray(), $result->items),
            'page' => $result->page->toArray(),
            'currentAdminUsername' => $this->currentAdminUsername(),
            'actorStatusOptions' => FilterEnums::options(FilterEnums::ACTOR_STATUSES),
        ]);
    }

    protected function extraShowViewData(array $item, string $actorRef, string $actorCode): array
    {
        $current = $this->currentAdminUsername();

        return [
            'isCurrentAdmin' => is_string($current) && strcasecmp($current, $actorRef) === 0,
        ];
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'username' => ['required', 'string', 'regex:/^[A-Za-z0-9._@-]{3,64}$/'],
            'displayName' => ['required', 'string', 'max:120'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->adminsService->create(
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
                'adminUsername' => 'You cannot change your own status.',
            ]);
        }

        return $this->updateActorStatus($payload['adminUsername'], $payload, 'Admin status %s updated to %s.');
    }

    protected function actorType(): string
    {
        return 'ADMIN';
    }

    protected function indexView(): string
    {
        return 'backoffice.admins.index';
    }

    protected function showView(): string
    {
        return 'backoffice.admins.show';
    }

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
