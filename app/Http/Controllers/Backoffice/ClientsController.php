<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\ClientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientsController extends Controller
{
    public function __construct(private readonly ClientsService $service) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'query'       => ['nullable', 'string', 'max:120'],
            'status'      => ['nullable', 'string', 'max:50'],
            'createdFrom' => ['nullable', 'string', 'max:50'],
            'createdTo'   => ['nullable', 'string', 'max:50'],
            'limit'       => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'      => ['nullable', 'string', 'max:500'],
            'sort'        => ['nullable', 'string', 'max:50'],
        ]);

        $filters['limit'] = $filters['limit'] ?? 25;

        $data = $this->service->list($filters);

        return view('backoffice.clients.index', [
            'filters' => $filters,
            'items'   => $data['items'] ?? [],
            'page'    => $data['page'] ?? ['hasMore' => false],
        ]);
    }

    public function show(string $clientCode)
    {
        $item = $this->service->show(
            clientCode: $clientCode,
            correlationId: (string) Str::uuid(),
        );

        return view('backoffice.clients.show', [
            'item' => $item,
        ]);
    }
}
