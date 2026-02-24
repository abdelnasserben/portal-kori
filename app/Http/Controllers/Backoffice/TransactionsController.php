<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\TransactionsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionsController extends Controller
{
    public function __construct(private readonly TransactionsService $service) {}

    public function index(Request $request)
    {
        // Validation légère (format/typage), pas de règles métier.
        $validated = $request->validate([
            'query'        => ['nullable', 'string', 'max:120'],
            'type'         => ['nullable', 'string', 'max:50'],
            'status'       => ['nullable', 'string', 'max:50'],
            'actorType'    => ['nullable', 'string', 'max:50'],
            'actorRef'     => ['nullable', 'string', 'max:120'],
            'terminalUid'  => ['nullable', 'string', 'max:120'],
            'cardUid'      => ['nullable', 'string', 'max:120'],
            'merchantCode' => ['nullable', 'string', 'max:50'],
            'agentCode'    => ['nullable', 'string', 'max:50'],
            'clientPhone'  => ['nullable', 'string', 'max:30'],

            // On laisse l’API interpréter les formats exacts de date/amount
            'from'         => ['nullable', 'string', 'max:50'],
            'to'           => ['nullable', 'string', 'max:50'],
            'min'          => ['nullable', 'string', 'max:50'],
            'max'          => ['nullable', 'string', 'max:50'],

            'limit'        => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'       => ['nullable', 'string', 'max:500'],
            'sort'         => ['nullable', 'string', 'max:50'],
        ]);

        // Valeurs par défaut (UX)
        $validated['limit'] = $validated['limit'] ?? 25;

        $data = $this->service->list($validated);

        return view('backoffice.transactions.index', [
            'filters' => $validated,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
        ]);
    }

    public function show(string $transactionRef)
    {
        $item = $this->service->show(
            transactionRef: $transactionRef,
            correlationId: (string) Str::uuid(),
        );

        return view('backoffice.transactions.show', [
            'item' => $item,
        ]);
    }
}
