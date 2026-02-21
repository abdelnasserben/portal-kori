<?php

namespace App\Http\Controllers;

use App\Services\KoriApiClient;

class HealthController extends Controller
{
    public function __construct(
        private readonly KoriApiClient $api
    ) {}

    public function __invoke()
    {
        $health = $this->api->get('/api/v1/backoffice/transactions');

        return view('health', [
            'health' => $health,
        ]);
    }
}