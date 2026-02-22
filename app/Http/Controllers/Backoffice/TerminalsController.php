<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\TerminalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TerminalsController extends Controller
{
    public function __construct(private readonly TerminalsService $service) {}

    public function create(Request $request)
    {
        $query = $request->validate([
            'merchantCode' => ['nullable', 'string', 'max:16'],
        ]);

        return view('backoffice.terminals.create', [
            'merchantCode' => $query['merchantCode'] ?? '',
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'merchantCode' => ['required', 'string', 'max:16'],
        ]);

        $idempotencyKey = (string) Str::uuid();
        $correlationId = (string) Str::uuid();

        $created = $this->service->create(
            merchantCode: $payload['merchantCode'],
            idempotencyKey: $idempotencyKey,
            correlationId: $correlationId,
        );

        return view('backoffice.terminals.created', [
            'created' => $created,
            'meta' => [
                'merchantCode'   => $payload['merchantCode'],
                'idempotencyKey' => $idempotencyKey,
                'correlationId'  => $correlationId,
            ],
        ]);
    }
}
