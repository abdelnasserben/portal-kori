<?php

namespace App\Http\Requests\Backoffice;

use App\Support\Backoffice\FilterEnums;
use Illuminate\Foundation\Http\FormRequest;

class ActorStatusUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'targetStatus' => ['required', 'string', 'in:' . implode(',', FilterEnums::ACTOR_STATUSES)],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
