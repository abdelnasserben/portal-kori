<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\AuditEventsIndexRequest;
use App\Services\Backoffice\AuditEventsService;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Http\Request;

class AuditEventsController extends Controller
{
    public function __construct(private readonly AuditEventsService $service) {}

    public function index(AuditEventsIndexRequest $request)
    {
        $filtersUi  = $request->filtersForUi();
        $filtersApi = $request->filtersForApi();

        $data = $this->service->list($filtersApi);

        return view('backoffice.audits.index', [
            'filters' => $filtersUi,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
            'actorTypeOptions' => FilterEnums::options(FilterEnums::ACTOR_TYPES),
        ]);
    }

    public function show(Request $request, string $eventRef)
    {
        $details = $this->service->findByEventRef($eventRef);

        if (is_null($details)) {
            $snapshot = $request->query('snapshot');

            if (is_string($snapshot) && $snapshot !== '') {
                $decoded = json_decode(base64_decode($snapshot, true) ?: '', true);
                if (is_array($decoded)) {
                    $details = $decoded;
                }
            }
        }

        return view('backoffice.audits.show', [
            'eventRef' => $eventRef,
            'item' => $details,
        ]);
    }
}
