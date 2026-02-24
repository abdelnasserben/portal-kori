<?php

namespace App\Http\Requests\Backoffice;

use App\Http\Requests\Concerns\DateFilterNormalizer;
use App\Http\Requests\Concerns\DateRangeFilters;
use Illuminate\Foundation\Http\FormRequest;

class LedgerIndexRequest extends FormRequest
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
            'from'            => false,
            'to'              => true,
            'beforeCreatedAt' => true,
        ];
    }

    public function rules(): array
    {
        return [
            'accountType'          => ['nullable', 'string', 'max:50'],
            'ownerRef'             => ['nullable', 'string', 'max:120'],
            'transactionType'      => ['nullable', 'string', 'max:50'],

            'from'                 => ['nullable', 'string', 'max:60'],
            'to'                   => ['nullable', 'string', 'max:60'],

            'minAmount'            => ['nullable', 'numeric'],
            'maxAmount'            => ['nullable', 'numeric'],

            'view'                 => ['nullable', 'string', 'max:50'],

            'beforeCreatedAt'      => ['nullable', 'string', 'max:60'],
            'beforeTransactionId'  => ['nullable', 'string', 'max:120'],

            'limit'                => ['nullable', 'integer', 'min:1', 'max:200'],

            // internes ISO
            'fromIso'             => ['nullable', 'string', 'max:60'],
            'toIso'               => ['nullable', 'string', 'max:60'],
            'beforeCreatedAtIso'  => ['nullable', 'string', 'max:60'],
        ];
    }
}
