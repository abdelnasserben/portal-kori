<?php

namespace App\Http\Requests\Concerns;

trait DateRangeFilters
{
    /**
     * Chaque FormRequest doit définir :
     * return [
     *     'from' => false,
     *     'to' => true,
     *     'beforeCreatedAt' => true,
     * ];
     */
    abstract protected function dateFields(): array;

    protected function prepareForValidation(): void
    {
        $merged = [];

        foreach ($this->dateFields() as $field => $endOfDay) {
            $merged[$field . 'Iso'] = $this->normalizeDateFilter(
                $this->input($field),
                $endOfDay
            );
        }

        $this->merge($merged);
    }

    public function filtersForUi(): array
    {
        $v = $this->validated();
        $v['limit'] = $v['limit'] ?? 25;

        foreach ($this->dateFields() as $field => $_) {
            unset($v[$field . 'Iso']);
        }

        return $v;
    }

    public function filtersForApi(): array
    {
        $v = $this->validated();
        $v['limit'] = $v['limit'] ?? 25;

        foreach ($this->dateFields() as $field => $_) {
            $iso = $field . 'Iso';

            if (!empty($v[$iso])) {
                $v[$field] = $v[$iso];
            }

            unset($v[$iso]);
        }

        return $v;
    }
}
