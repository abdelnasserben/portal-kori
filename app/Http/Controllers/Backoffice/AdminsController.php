<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Auth\JwtDecoder;
use App\Services\Backoffice\AdminsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminsController extends Controller
{
    private const ALLOWED_STATUSES = ['ACTIVE', 'SUSPENDED', 'CLOSED'];

    public function __construct(
        private readonly AdminsService $service,
        private readonly JwtDecoder $decoder,
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

        return view('backoffice.admins.show', [
            'item' => $item,
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

    public function updateStatus(Request $request)
    {
        $payload = $request->validate([
            'adminUsername' => ['required', 'string', 'regex:/^[A-Za-z0-9._@-]{3,64}$/'],
            'targetStatus' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

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
