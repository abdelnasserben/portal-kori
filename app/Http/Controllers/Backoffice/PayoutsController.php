<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\PayoutsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayoutsController extends Controller
{
    public function __construct(private readonly PayoutsService $service) {}

    public function create(Request $request)
    {
        return view('backoffice.payouts.create', [
            'prefillAgentCode' => $request->query('agentCode'),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'agentCode' => ['required', 'string', 'max:16'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->requestAgentPayout(
            agentCode: $payload['agentCode'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.payouts.created', [
            'created' => $created,
            'meta' => [
                'agentCode' => $payload['agentCode'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId' => $correlationId,
            ],
        ]);
    }

    public function complete(string $payoutId)
    {
        $this->service->complete(
            payoutId: $payoutId,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf('Payout %s marked as completed.', $payoutId));
    }

    public function fail(Request $request, string $payoutId)
    {
        $payload = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $this->service->fail(
            payoutId: $payoutId,
            reason: $payload['reason'],
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf('Payout %s marked as failed.', $payoutId));
    }
}
