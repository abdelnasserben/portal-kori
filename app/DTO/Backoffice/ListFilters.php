<?php

namespace App\DTO\Backoffice;

final readonly class ListFilters
{
    public function __construct(public array $values) {}

    public static function fromArray(array $values): self
    {
        return new self($values);
    }

    public function toQuery(): array
    {
        return array_filter($this->values, fn ($value) => !is_null($value) && $value !== '');
    }

    public function toArray(): array
    {
        return $this->values;
    }
}
