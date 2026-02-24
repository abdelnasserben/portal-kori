<?php

namespace App\Http\Requests\Backoffice;

use App\Http\Requests\Concerns\DateFilterNormalizer;
use App\Http\Requests\Concerns\DateRangeFilters;
use Illuminate\Foundation\Http\FormRequest;

class TransactionsIndexRequest extends FormRequest
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
            'query'        => ['nullable', 'string', 'max:120'],
            'type'         => ['nullable', 'string', 'max:50'],
            'status'       => ['nullable', 'string', 'max:50'],
            'actorType'    => ['nullable', 'string', 'max:50'],
            'actorRef'     => ['nullable', 'string', 'max:120'],
            'terminalUid'  => ['nullable', 'string', 'max:120'],
            'cardUid'      => ['nullable', 'string', 'max:120'],
            'merchantCode' => ['nullable', 'string', 'max:50'],
            'agentCode'    => ['nullable', 'string', 'max:50'],
            'clientPhone'  => ['nullable', 'string', 'max:30'],

            'from'         => ['nullable', 'string', 'max:50'],
            'to'           => ['nullable', 'string', 'max:50'],
            'min'          => ['nullable', 'string', 'max:50'],
            'max'          => ['nullable', 'string', 'max:50'],

            'limit'        => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'       => ['nullable', 'string', 'max:500'],
            'sort'         => ['nullable', 'string', 'max:50'],

            // internes ISO
            'fromIso'      => ['nullable', 'string', 'max:60'],
            'toIso'        => ['nullable', 'string', 'max:60'],
        ];
    }
}
