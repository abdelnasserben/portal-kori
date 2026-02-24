<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\CardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CardsController extends Controller
{
    public function __construct(private readonly CardsService $service) {}

    public function index()
    {
        return view('backoffice.cards.index');
    }

    public function updateStatus(Request $request)
    {
        $payload = $request->validate([
            'cardUid' => ['required', 'string', 'max:120'],
            'targetStatus' => ['required', 'string', 'max:50'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $result = $this->service->updateStatus(
            cardUid: $payload['cardUid'],
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut carte %s mis à jour (%s → %s).',
            $payload['cardUid'],
            $result['previousStatus'] ?? '—',
            $result['newStatus'] ?? $payload['targetStatus'],
        ));
    }

    public function unblock(Request $request)
    {
        $payload = $request->validate([
            'cardUid' => ['required', 'string', 'max:120'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $result = $this->service->unblock(
            cardUid: $payload['cardUid'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Carte %s débloquée (%s → %s).',
            $payload['cardUid'],
            $result['previousStatus'] ?? '—',
            $result['newStatus'] ?? '—',
        ));
    }
}
