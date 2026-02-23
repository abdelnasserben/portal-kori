<?php

namespace App\Services\Backoffice;

use App\Services\KoriApiClient;

class AuditEventsService
{
    public function __construct(private readonly KoriApiClient $api) {}

    /**
     * @return array{items: array<int, array>, page?: array{nextCursor?: string, hasMore?: bool}}
     */
    public function list(array $filters): array
    {
        $query = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

        return $this->api->get('/api/v1/backoffice/audit-events', $query);
    }

    public function findByEventRef(string $eventRef): ?array
    {
        $data = $this->list([
            'eventRef' => $eventRef,
            'limit' => 1,
        ]);

        $items = $data['items'] ?? [];

        if (!is_array($items) || empty($items)) {
            return null;
        }

        foreach ($items as $item) {
            if (is_array($item) && (($item['eventRef'] ?? null) === $eventRef)) {
                return $item;
            }
        }

        return is_array($items[0] ?? null) ? $items[0] : null;
    }
}
