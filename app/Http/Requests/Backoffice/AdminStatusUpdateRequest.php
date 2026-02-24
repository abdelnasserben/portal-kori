<?php

namespace App\Http\Requests\Backoffice;

class AdminStatusUpdateRequest extends ActorStatusUpdateRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'adminUsername' => ['required', 'string', 'regex:/^[A-Za-z0-9._@-]{3,64}$/'],
        ]);
    }
}
