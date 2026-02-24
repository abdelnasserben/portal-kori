<?php

namespace App\Http\Requests\Backoffice;

use App\DTO\Backoffice\ListFilters;
use Illuminate\Foundation\Http\FormRequest;

class ListFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'string', 'max:50'],
            'createdFrom' => ['nullable', 'string', 'max:50'],
            'createdTo' => ['nullable', 'string', 'max:50'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();
        $validated['limit'] = $validated['limit'] ?? 25;

        return $validated;
    }

    public function toDto(): ListFilters
    {
        return ListFilters::fromArray($this->validatedWithDefaults());
    }
}
