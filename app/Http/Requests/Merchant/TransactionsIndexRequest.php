<?php

namespace App\Http\Requests\Merchant;

use App\Http\Requests\Concerns\DateFilterNormalizer;
use App\Http\Requests\Concerns\DateRangeFilters;
use App\Support\Backoffice\FilterEnums;
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
            'type'    => ['nullable', 'string', 'in:' . implode(',', FilterEnums::TRANSACTION_TYPES)],
            'status'  => ['nullable', 'string', 'in:' . implode(',', FilterEnums::TRANSACTION_STATUSES)],
            'from'    => ['nullable', 'string', 'max:50'],
            'to'      => ['nullable', 'string', 'max:50'],
            'min'     => ['nullable', 'string', 'max:50'],
            'max'     => ['nullable', 'string', 'max:50'],
            'limit'   => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'  => ['nullable', 'string', 'max:500'],
            'sort'    => ['nullable', 'string', 'max:50'],

            'fromIso' => ['nullable', 'string', 'max:60'],
            'toIso'   => ['nullable', 'string', 'max:60'],
        ];
    }
}
