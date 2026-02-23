<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\MerchantsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantsController extends Controller
{
    private const ALLOWED_STATUSES = ['ACTIVE', 'SUSPENDED', 'CLOSED'];

    public function __construct(private readonly MerchantsService $service) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'query'       => ['nullable', 'string', 'max:120'],
            'status'      => ['nullable', 'string', 'max:50'],
            'createdFrom' => ['nullable', 'string', 'max:50'], // format géré par l’API
            'createdTo'   => ['nullable', 'string', 'max:50'],
            'limit'       => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'      => ['nullable', 'string', 'max:500'],
            'sort'        => ['nullable', 'string', 'max:50'],
        ]);

        $filters['limit'] = $filters['limit'] ?? 25;

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

    public function updateStatus(Request $request, string $merchantCode)
    {
        $payload = $request->validate([
            'targetStatus' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

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
