<?php

namespace App\Http\Controllers\Backoffice;

use App\Exceptions\KoriApiException;
use App\Http\Controllers\Controller;
use App\Services\Backoffice\LookupsService;
use App\Support\ApiErrorPresenter;
use Illuminate\Http\Request;

class LookupsController extends Controller
{
    private const TYPE_TO_ROUTE = [
        'CLIENT' => ['name' => 'admin.clients.show', 'parameter' => 'clientCode'],
        'MERCHANT' => ['name' => 'admin.merchants.show', 'parameter' => 'merchantCode'],
        'AGENT' => ['name' => 'admin.agents.show', 'parameter' => 'agentCode'],
        'TERMINAL' => ['name' => 'admin.terminals.show', 'parameter' => 'terminalUid'],
        'ADMIN' => ['name' => 'admin.admins.show', 'parameter' => 'adminUsername'],
    ];

    public function __construct(private readonly LookupsService $service) {}

    public function index(Request $request)
    {
        $payload = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:120'],
            'type' => ['nullable', 'string', 'max:30', 'in:' . implode(',', self::LOOKUP_TYPES)],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $limit = $payload['limit'] ?? 10;

        try {
            $data = $this->service->search(
                query: $payload['q'],
                type: $payload['type'] ?? null,
                limit: $limit,
            );
        } catch (KoriApiException $e) {
            if (in_array($e->status, [401, 403, 500], true)) {
                return response()->view('errors.api', [
                    'error' => ApiErrorPresenter::fromException($e),
                ], $e->status);
            }

            throw $e;
        }

        $items = collect($data['items'] ?? [])->map(function (array $item) {
            $item['entityType'] = strtoupper((string) ($item['entityType'] ?? ''));
            $item['actorRef'] = $item['entityRef'] ?? null;
            $item['routeTarget'] = $this->resolveRoute($item);

            return $item;
        })->all();

        if (count($items) === 1) {
            $target = $this->resolveRoute($items[0]);

            if (!is_null($target)) {
                return redirect()->route($target['name'], [$target['parameter'] => $target['value']]);
            }
        }

        return view('backoffice.lookups.index', [
            'query' => $payload['q'],
            'selectedType' => $payload['type'] ?? null,
            'limit' => $limit,
            'items' => $items,
            'types' => self::LOOKUP_TYPES,
        ]);
    }

    private function resolveRoute(array $item): ?array
    {
        $entityType = strtoupper((string) ($item['entityType'] ?? ''));
        $entityRef = (string) ($item['entityRef'] ?? '');

        if ($entityRef === '' || !array_key_exists($entityType, self::TYPE_TO_ROUTE)) {
            return null;
        }

        return [
            ...self::TYPE_TO_ROUTE[$entityType],
            'value' => $entityRef,
        ];
    }

    private const LOOKUP_TYPES = [
        'CLIENT_CODE',
        'CARD_UID',
        'TERMINAL_UID',
        'TRANSACTION_REF',
        'MERCHANT_CODE',
        'AGENT_CODE',
        'ADMIN_USERNAME'
    ];
}
