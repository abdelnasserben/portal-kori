<?php

namespace App\Http\Requests\Backoffice;

use App\Http\Requests\Concerns\DateFilterNormalizer;
use App\Http\Requests\Concerns\DateRangeFilters;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Foundation\Http\FormRequest;

class AuditEventsIndexRequest extends FormRequest
{
    use DateFilterNormalizer;
    use DateRangeFilters;

    public function authorize(): bool
    {
        return true;
    }

    protected function dateFields(): array
    {
        return [
            'from' => false,
            'to'   => true,
        ];
    }

    public function rules(): array
    {
        return [
            'action'       => ['nullable', 'string', 'max:120'],
            'actorType'    => ['nullable', 'string', 'in:' . implode(',', FilterEnums::ACTOR_TYPES)],
            'actorRef'     => ['nullable', 'string', 'max:120'],
            'resourceType' => ['nullable', 'string', 'max:80'],
            'resourceRef'  => ['nullable', 'string', 'max:120'],
            'from'         => ['nullable', 'string', 'max:60'],
            'to'           => ['nullable', 'string', 'max:60'],
            'limit'        => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'       => ['nullable', 'string', 'max:500'],
            'sort'         => ['nullable', 'string', 'max:50'],

            // internes ISO
            'fromIso'      => ['nullable', 'string', 'max:60'],
            'toIso'        => ['nullable', 'string', 'max:60'],
        ];
    }
}
