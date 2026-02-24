<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\TransactionsIndexRequest;
use App\Services\Backoffice\TransactionsService;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Support\Str;

class TransactionsController extends Controller
{
    public function __construct(private readonly TransactionsService $service) {}

    public function index(TransactionsIndexRequest $request)
    {
        $filtersUi  = $request->filtersForUi();
        $filtersApi = $request->filtersForApi();

        $data = $this->service->list($filtersApi);

        return view('backoffice.transactions.index', [
            'filters' => $filtersUi,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
            'transactionTypeOptions' => FilterEnums::options(FilterEnums::TRANSACTION_TYPES),
            'transactionStatusOptions' => FilterEnums::options(FilterEnums::TRANSACTION_STATUSES),
            'actorTypeOptions' => FilterEnums::options(FilterEnums::ACTOR_TYPES),
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
