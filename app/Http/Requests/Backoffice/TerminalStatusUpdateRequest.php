<?php

namespace App\Http\Requests\Backoffice;

class TerminalStatusUpdateRequest extends ActorStatusUpdateRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'terminalUid' => ['required', 'string', 'max:120'],
        ]);
    }
}
