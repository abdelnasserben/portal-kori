<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\AccountProfilesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountProfilesController extends Controller
{
    public function __construct(private readonly AccountProfilesService $service) {}

    public function index()
    {
        return view('backoffice.account-profiles.index');
    }

    public function updateStatus(Request $request)
    {
        $payload = $request->validate([
            'accountType' => ['required', 'string', 'max:50'],
            'ownerRef' => ['required', 'string', 'max:120'],
            'targetStatus' => ['required', 'string', 'max:50'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $result = $this->service->updateStatus(
            accountType: $payload['accountType'],
            ownerRef: $payload['ownerRef'],
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Statut profile %s/%s mis à jour (%s → %s).',
            $payload['accountType'],
            $payload['ownerRef'],
            $result['previousStatus'] ?? '—',
            $result['newStatus'] ?? $payload['targetStatus'],
        ));
    }
}
