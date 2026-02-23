<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\AuditEventsService;
use Illuminate\Http\Request;

class AuditEventsController extends Controller
{
    public function __construct(private readonly AuditEventsService $service) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'action' => ['nullable', 'string', 'max:120'],
            'actorType' => ['nullable', 'string', 'max:50'],
            'actorRef' => ['nullable', 'string', 'max:120'],
            'resourceType' => ['nullable', 'string', 'max:80'],
            'resourceRef' => ['nullable', 'string', 'max:120'],
            'from' => ['nullable', 'string', 'max:60'],
            'to' => ['nullable', 'string', 'max:60'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'string', 'max:50'],
        ]);

        $filters['limit'] = $filters['limit'] ?? 25;

        $data = $this->service->list($filters);

        return view('backoffice.audits.index', [
            'filters' => $filters,
            'items' => $data['items'] ?? [],
            'page' => $data['page'] ?? ['hasMore' => false],
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

