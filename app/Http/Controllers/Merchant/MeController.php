<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\TerminalsIndexRequest;
use App\Http\Requests\Merchant\TransactionsIndexRequest;
use App\Services\Merchant\MeService;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Support\Str;

class MeController extends Controller
{
    public function __construct(private readonly MeService $service) {}

    public function home()
    {
        $correlationId = (string) Str::uuid();

        return view('merchant.home', [
            'profile' => $this->service->profile($correlationId),
            'balance' => $this->service->balance($correlationId),
        ]);
    }

    public function profile()
    {
        return view('merchant.me.profile', [
            'item' => $this->service->profile((string) Str::uuid()),
        ]);
    }

    public function balance()
    {
        return view('merchant.me.balance', [
            'item' => $this->service->balance((string) Str::uuid()),
        ]);
    }

    public function transactions(TransactionsIndexRequest $request)
    {
        $merchantTransactionTypes = [
            'PAY_BY_CARD',
            'MERCHANT_WITHDRAW_AT_AGENT',
            'REVERSAL',
            'MERCHANT_TRANSFER',
        ];

        $filtersUi  = $request->filtersForUi();
        $filtersApi = $request->filtersForApi();

        $data = $this->service->transactions($filtersApi, (string) Str::uuid());

        return view('merchant.me.transactions', [
            'filters' => $filtersUi,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
            'transactionTypeOptions' => FilterEnums::options(
                array_values(array_intersect(
                    FilterEnums::TRANSACTION_TYPES,
                    $merchantTransactionTypes
                ))
            ),
            'transactionStatusOptions' => FilterEnums::options(FilterEnums::TRANSACTION_STATUSES),
        ]);
    }

    public function transaction(string $transactionRef)
    {
        return view('merchant.me.transaction-show', [
            'item' => $this->service->transaction($transactionRef, (string) Str::uuid()),
        ]);
    }

    public function terminals(TerminalsIndexRequest $request)
    {
        $filtersUi  = $request->filtersForUi();
        $filtersApi = $request->filtersForApi();

        $data = $this->service->terminals($filtersApi, (string) Str::uuid());

        return view('merchant.me.terminals', [
            'filters' => $filtersUi,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
            'statusOptions' => FilterEnums::options(FilterEnums::ACTOR_STATUSES),
        ]);
    }

    public function terminal(string $terminalUid)
    {
        return view('merchant.me.terminal-show', [
            'item' => $this->service->terminal($terminalUid, (string) Str::uuid()),
        ]);
    }
}
