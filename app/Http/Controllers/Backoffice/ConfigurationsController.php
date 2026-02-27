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
        return view('backoffice.configurations.index', [
            'fees' => $this->service->getFees((string) Str::uuid()),
            'commissions' => $this->service->getCommissions((string) Str::uuid()),
            'platform' => $this->service->getPlatform((string) Str::uuid()),
        ]);
    }

    public function updateFees(Request $request)
    {
        $payload = $request->validate([
            'cardEnrollmentPrice' => ['required', 'numeric'],
            'cardPaymentFeeRate' => ['required', 'numeric'],
            'cardPaymentFeeMin' => ['required', 'numeric'],
            'cardPaymentFeeMax' => ['required', 'numeric'],
            'merchantWithdrawFeeRate' => ['required', 'numeric'],
            'merchantWithdrawFeeMin' => ['required', 'numeric'],
            'merchantWithdrawFeeMax' => ['required', 'numeric'],
            'clientTransferFeeRate' => ['required', 'numeric'],
            'clientTransferFeeMin' => ['required', 'numeric'],
            'clientTransferFeeMax' => ['required', 'numeric'],
            'merchantTransferFeeRate' => ['required', 'numeric'],
            'merchantTransferFeeMin' => ['required', 'numeric'],
            'merchantTransferFeeMax' => ['required', 'numeric'],
            'cardPaymentFeeRefundable' => ['nullable', 'boolean'],
            'merchantWithdrawFeeRefundable' => ['nullable', 'boolean'],
            'cardEnrollmentPriceRefundable' => ['nullable', 'boolean'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $payload['cardPaymentFeeRefundable'] = $request->boolean('cardPaymentFeeRefundable');
        $payload['merchantWithdrawFeeRefundable'] = $request->boolean('merchantWithdrawFeeRefundable');
        $payload['cardEnrollmentPriceRefundable'] = $request->boolean('cardEnrollmentPriceRefundable');

        $this->service->updateFees($this->trimReason($payload), (string) Str::uuid());

        return to_route('admin.config.index')->with('status_success', 'Fee settings updated successfully.');
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

        return to_route('admin.config.index')->with('status_success', 'Commission settings updated successfully.');
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

        return to_route('admin.config.index')->with('status_success', 'Platform configuration updated successfully.');
    }

    private function trimReason(array $payload): array
    {
        if (array_key_exists('reason', $payload) && ($payload['reason'] ?? '') === '') {
            unset($payload['reason']);
        }

        return $payload;
    }
}
