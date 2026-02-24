<?php

namespace App\Http\Requests\Concerns;

use Carbon\CarbonImmutable;

trait DateFilterNormalizer
{
    /**
     * Normalise une date "YYYY-MM-DD" en ISO 8601 Zulu (UTC) en start/endOfDay.
     * Si ce n'est pas ce format, on renvoie la valeur telle quelle (l'API gère le reste).
     */
    protected function normalizeDateFilter(mixed $value, bool $endOfDay): mixed
    {
        if (!is_string($value) || $value === '') {
            return $value;
        }

        // On ne touche qu'aux dates simples (UX)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        $date = CarbonImmutable::createFromFormat('Y-m-d', $value, 'UTC');

        if (!$date) {
            return $value;
        }

        return ($endOfDay ? $date->endOfDay() : $date->startOfDay())
            ->toIso8601ZuluString();
    }
}
