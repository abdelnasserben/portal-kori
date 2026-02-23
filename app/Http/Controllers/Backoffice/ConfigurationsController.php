<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\ConfigurationsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConfigurationsController extends Controller
{
    public function __construct(private readonly ConfigurationsService $service) {}

    public function index()
    {
        $fees = $this->service->getFees((string) Str::uuid());
        $commissions = $this->service->getCommissions((string) Str::uuid());
        $platform = $this->service->getPlatform((string) Str::uuid());

        return view('backoffice.configurations.index', [
            'fees' => $fees ?? [],
            'feesNotConfigured' => is_null($fees),

            'commissions' => $commissions ?? [],
            'commissionsNotConfigured' => is_null($commissions),

            'platform' => $platform ?? [],
            'platformNotConfigured' => is_null($platform),
        ]);
    }

    public function updateFees(Request $request)
    {
        $payload = $request->validate([
            'cardEnrollmentPrice' => ['required', 'numeric', 'min:0'],

            'cardPaymentFeeRate' => ['required', 'numeric', 'min:0'],
            'cardPaymentFeeMin' => ['required', 'numeric', 'min:0', 'lte:cardPaymentFeeMax'],
            'cardPaymentFeeMax' => ['required', 'numeric', 'min:0', 'gte:cardPaymentFeeMin'],

            'merchantWithdrawFeeRate' => ['required', 'numeric', 'min:0'],
            'merchantWithdrawFeeMin' => ['required', 'numeric', 'min:0', 'lte:merchantWithdrawFeeMax'],
            'merchantWithdrawFeeMax' => ['required', 'numeric', 'min:0', 'gte:merchantWithdrawFeeMin'],

            'clientTransferFeeRate' => ['required', 'numeric', 'min:0'],
            'clientTransferFeeMin' => ['required', 'numeric', 'min:0', 'lte:clientTransferFeeMax'],
            'clientTransferFeeMax' => ['required', 'numeric', 'min:0', 'gte:clientTransferFeeMin'],

            'merchantTransferFeeRate' => ['required', 'numeric', 'min:0'],
            'merchantTransferFeeMin' => ['required', 'numeric', 'min:0', 'lte:merchantTransferFeeMax'],
            'merchantTransferFeeMax' => ['required', 'numeric', 'min:0', 'gte:merchantTransferFeeMin'],

            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        
        $payload['cardPaymentFeeRefundable'] = $request->boolean('cardPaymentFeeRefundable');
        $payload['merchantWithdrawFeeRefundable'] = $request->boolean('merchantWithdrawFeeRefundable');
        $payload['cardEnrollmentPriceRefundable'] = $request->boolean('cardEnrollmentPriceRefundable');

        $this->service->updateFees($this->trimReason($payload), (string) Str::uuid());

        return to_route('admin.config.index')->with('status_success', 'Configuration des frais mise à jour.');
    }

    public function updateCommissions(Request $request)
    {
        $payload = $request->validate([
            'cardEnrollmentAgentCommission' => ['required', 'numeric'],
            'merchantWithdrawCommissionRate' => ['required', 'numeric'],
            'merchantWithdrawCommissionMin' => ['nullable', 'numeric'],
            'merchantWithdrawCommissionMax' => ['nullable', 'numeric'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->service->updateCommissions($this->trimReason($payload), (string) Str::uuid());

        return to_route('admin.config.index')->with('status_success', 'Configuration des commissions mise à jour.');
    }

    public function updatePlatform(Request $request)
    {
        $payload = $request->validate([
            'agentCashLimitGlobal' => ['required', 'numeric'],
            'clientTransferMinPerTransaction' => ['required', 'numeric'],
            'clientTransferMaxPerTransaction' => ['required', 'numeric'],
            'clientTransferDailyMax' => ['required', 'numeric'],
            'merchantTransferMinPerTransaction' => ['required', 'numeric'],
            'merchantTransferMaxPerTransaction' => ['required', 'numeric'],
            'merchantTransferDailyMax' => ['required', 'numeric'],
            'merchantWithdrawMinPerTransaction' => ['required', 'numeric'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->service->updatePlatform($this->trimReason($payload), (string) Str::uuid());

        return to_route('admin.config.index')->with('status_success', 'Configuration de la plateforme mise à jour.');
    }

    private function trimReason(array $payload): array
    {
        if (array_key_exists('reason', $payload) && ($payload['reason'] ?? '') === '') {
            unset($payload['reason']);
        }

        return $payload;
    }
}
