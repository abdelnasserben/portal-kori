<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class ActorStatusUpdateRequest extends FormRequest
{
    private const ALLOWED_STATUSES = ['ACTIVE', 'SUSPENDED', 'CLOSED'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'targetStatus' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
