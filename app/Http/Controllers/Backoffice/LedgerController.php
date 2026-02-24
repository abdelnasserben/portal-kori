<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\LedgerIndexRequest;
use App\Services\Backoffice\LedgerService;
use App\Support\Backoffice\FilterEnums;

class LedgerController extends Controller
{
    public function __construct(private readonly LedgerService $service) {}

    public function index(LedgerIndexRequest $request)
    {
        $filtersUi  = $request->filtersForUi();
        $filtersApi = $request->filtersForApi();

        $items = [];
        $scope = null;
        $next = ['beforeCreatedAt' => null, 'beforeTransactionId' => null];
        $balance = null;

        if (!empty($filtersApi['accountType']) && !empty($filtersApi['ownerRef'])) {
            $balance = $this->service->getBalance(
                accountType: $filtersApi['accountType'],
                ownerRef: $filtersApi['ownerRef'],
            );

            $history = $this->service->searchTransactions($filtersApi);
            $items = $history['items'] ?? [];
            $scope = $history['ledgerScope'] ?? null;
            $next = [
                'beforeCreatedAt' => $history['nextBeforeCreatedAt'] ?? null,
                'beforeTransactionId' => $history['nextBeforeTransactionId'] ?? null,
            ];
        }

        return view('backoffice.ledger.index', [
            'filters' => $filtersUi,
            'balance' => $balance,
            'scope'   => $scope,
            'items'   => $items,
            'next'    => $next,
            'accountTypeOptions' => FilterEnums::options(FilterEnums::LEDGER_ACCOUNT_TYPES),
            'transactionTypeOptions' => FilterEnums::options(FilterEnums::TRANSACTION_TYPES),
            'transactionViewOptions' => FilterEnums::options(FilterEnums::TRANSACTION_HISTORY_VIEWS),
        ]);
    }
}
