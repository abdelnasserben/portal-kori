<?php

namespace App\Http\Controllers\Backoffice;

use App\Exceptions\KoriApiException;
use App\Http\Controllers\Controller;
use App\Services\Backoffice\DashboardService;
use App\Support\ApiErrorPresenter;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $service) {}

    public function __invoke()
    {
        try {
            $dashboard = $this->service->summary((string) Str::uuid());

            return view('admin.home', ['dashboard' => $dashboard]);
        } catch (KoriApiException $e) {
            return redirect()
                ->route('admin.transactions.index')
                ->with('api_error', ApiErrorPresenter::fromException(
                    $e,
                    'Back office dashboard unavailable, redirecting to transaction list.'
                ));
        }
    }
}
