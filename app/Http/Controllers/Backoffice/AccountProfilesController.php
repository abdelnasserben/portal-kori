<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\AccountProfilesService;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountProfilesController extends Controller
{
    public function __construct(private readonly AccountProfilesService $service) {}

    public function index()
    {
        $allowedAccountTypes = $this->allowedAccountTypes();

        return view('backoffice.account-profiles.index', [
            'accountTypeOptions'  => FilterEnums::options($allowedAccountTypes),
            'targetStatusOptions' => FilterEnums::options(FilterEnums::ACTOR_STATUSES),
        ]);
    }

    public function updateStatus(Request $request)
    {
        $allowedAccountTypes = $this->allowedAccountTypes();

        $payload = $request->validate([
            'accountType'   => ['required', Rule::in($allowedAccountTypes)],
            'ownerRef'      => ['required', 'string', 'max:120'],
            'targetStatus'  => ['required', Rule::in(FilterEnums::ACTOR_STATUSES)],
            'reason'        => ['nullable', 'string', 'max:255'],
        ]);

        $result = $this->service->updateStatus(
            accountType: $payload['accountType'],
            ownerRef: $payload['ownerRef'],
            targetStatus: $payload['targetStatus'],
            reason: $payload['reason'] ?? null,
            correlationId: (string) Str::uuid(),
        );

        return back()->with('status_success', sprintf(
            'Profile status %s/%s updated (%s → %s).',
            $payload['accountType'],
            $payload['ownerRef'],
            $result['previousStatus'] ?? '—',
            $result['newStatus'] ?? $payload['targetStatus'],
        ));
    }

    /**
     * Ledger account types allowed for manual status updates
     * (exclude platform/system and any clearing accounts).
     */
    private function allowedAccountTypes(): array
    {
        return collect(FilterEnums::LEDGER_ACCOUNT_TYPES)
            ->reject(fn (string $t) =>
                str_starts_with($t, 'PLATFORM_') || str_contains($t, 'CLEARING')
            )
            ->values()
            ->all();
    }
}