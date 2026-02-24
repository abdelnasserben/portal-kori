<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\LedgerService;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function __construct(private readonly LedgerService $service) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'accountType' => ['nullable', 'string', 'max:50'],
            'ownerRef' => ['nullable', 'string', 'max:120'],
            'transactionType' => ['nullable', 'string', 'max:50'],
            'from' => ['nullable', 'string', 'max:60'],
            'to' => ['nullable', 'string', 'max:60'],
            'minAmount' => ['nullable', 'numeric'],
            'maxAmount' => ['nullable', 'numeric'],
            'view' => ['nullable', 'string', 'max:50'],
            'beforeCreatedAt' => ['nullable', 'string', 'max:60'],
            'beforeTransactionId' => ['nullable', 'string', 'max:120'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $filters['limit'] = $filters['limit'] ?? 25;

        $items = [];
        $scope = null;
        $next = ['beforeCreatedAt' => null, 'beforeTransactionId' => null];
        $balance = null;

        if (!empty($filters['accountType']) && !empty($filters['ownerRef'])) {
            $balance = $this->service->getBalance(
                accountType: $filters['accountType'],
                ownerRef: $filters['ownerRef'],
            );

            $history = $this->service->searchTransactions($filters);
            $items = $history['items'] ?? [];
            $scope = $history['ledgerScope'] ?? null;
            $next = [
                'beforeCreatedAt' => $history['nextBeforeCreatedAt'] ?? null,
                'beforeTransactionId' => $history['nextBeforeTransactionId'] ?? null,
            ];
        }

        return view('backoffice.ledger.index', [
            'filters' => $filters,
            'balance' => $balance,
            'scope' => $scope,
            'items' => $items,
            'next' => $next,
        ]);
    }
}
