<?php

namespace App\Http\Requests\Backoffice;

use App\DTO\Backoffice\ListFilters;
use App\Http\Requests\Concerns\DateFilterNormalizer;
use App\Support\Backoffice\FilterEnums;
use Illuminate\Foundation\Http\FormRequest;

class ListFiltersRequest extends FormRequest
{
    use DateFilterNormalizer;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query'          => ['nullable', 'string', 'max:120'],
            'status'         => ['nullable', 'string', 'in:' . implode(',', FilterEnums::ACTOR_STATUSES)],
            'createdFrom'    => ['nullable', 'string', 'max:50'], // UI
            'createdTo'      => ['nullable', 'string', 'max:50'], // UI
            'limit'          => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'         => ['nullable', 'string', 'max:500'],
            'sort'           => ['nullable', 'string', 'max:50'],

            // internes
            'createdFromIso' => ['nullable', 'string', 'max:60'],
            'createdToIso'   => ['nullable', 'string', 'max:60'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'createdFromIso' => $this->normalizeDateFilter($this->input('createdFrom'), false),
            'createdToIso'   => $this->normalizeDateFilter($this->input('createdTo'), true),
        ]);
    }

    public function filtersForUi(): array
    {
        $v = $this->validated();
        $v['limit'] = $v['limit'] ?? 25;
        unset($v['createdFromIso'], $v['createdToIso']);
        return $v;
    }

    public function toDto(): ListFilters
    {
        $v = $this->validated();
        $v['limit'] = $v['limit'] ?? 25;

        if (!empty($v['createdFromIso'])) $v['createdFrom'] = $v['createdFromIso'];
        if (!empty($v['createdToIso']))   $v['createdTo']   = $v['createdToIso'];

        unset($v['createdFromIso'], $v['createdToIso']);

        return ListFilters::fromArray($v);
    }
}
