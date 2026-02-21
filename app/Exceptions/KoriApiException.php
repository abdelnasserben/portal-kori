<?php

namespace App\Exceptions;

use Exception;

class KoriApiException extends Exception
{
    public function __construct(
        public readonly int $status,
        public readonly ?array $payload = null,
        string $message = 'Kori API error',
        ?Exception $previous = null,
    ) {
        parent::__construct($message, $status, $previous);
    }
}