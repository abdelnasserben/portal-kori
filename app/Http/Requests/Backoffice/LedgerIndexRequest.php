<?php

namespace App\Http\Requests\Backoffice;

use App\Http\Requests\Concerns\DateFilterNormalizer;
use App\Http\Requests\Concerns\DateRangeFilters;
use App\Support\Backoffice\FilterEnums;
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
            'accountType'          => ['nullable', 'string', 'in:' . implode(',', FilterEnums::LEDGER_ACCOUNT_TYPES)],
            'ownerRef'             => ['nullable', 'string', 'max:120'],
            'transactionType'      => ['nullable', 'string', 'in:' . implode(',', FilterEnums::TRANSACTION_TYPES)],

            'from'                 => ['nullable', 'string', 'max:60'],
            'to'                   => ['nullable', 'string', 'max:60'],

            'minAmount'            => ['nullable', 'numeric'],
            'maxAmount'            => ['nullable', 'numeric'],

            'view'                 => ['nullable', 'string', 'in:' . implode(',', FilterEnums::TRANSACTION_HISTORY_VIEWS)],

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
