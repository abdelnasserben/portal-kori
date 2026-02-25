<?php

namespace App\Http\Requests\Merchant;

use App\Support\Backoffice\FilterEnums;
use Illuminate\Foundation\Http\FormRequest;

class TerminalsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'      => ['nullable', 'string', 'in:' . implode(',', FilterEnums::ACTOR_STATUSES)],
            'terminalUid' => ['nullable', 'string', 'max:120'],
            'limit'       => ['nullable', 'integer', 'min:1', 'max:200'],
            'cursor'      => ['nullable', 'string', 'max:500'],
            'sort'        => ['nullable', 'string', 'max:50'],
        ];
    }

    public function filtersForUi(): array
    {
        $v = $this->validated();
        $v['limit'] = $v['limit'] ?? 25;

        return $v;
    }

    public function filtersForApi(): array
    {
        return $this->filtersForUi();
    }
}
