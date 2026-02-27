<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\ClientRefundsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientRefundsController extends Controller
{
    public function __construct(private readonly ClientRefundsService $service) {}

    public function create(Request $request)
    {
        return view('backoffice.client-refunds.create', [
            'prefillClientCode' => $request->query('clientCode'),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'clientCode' => ['required', 'string', 'max:16'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->request(
            clientCode: $payload['clientCode'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.client-refunds.created', [
            'created' => $created,
            'meta' => [
                'clientCode' => $payload['clientCode'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId' => $correlationId,
            ],
        ]);
    }

    public function complete(string $refundId)
    {
        $this->service->complete(
            refundId: $refundId,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf('Refund %s marked as completed.', $refundId));
    }

    public function fail(Request $request, string $refundId)
    {
        $payload = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $this->service->fail(
            refundId: $refundId,
            reason: $payload['reason'],
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf('Refund %s marked as failed.', $refundId));
    }
}
