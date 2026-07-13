<?php

namespace App\Models\Concerns;

trait HasTranslations
{
    public function tr(string $field, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $translations = $this->translations ?? [];

        return (string) ($translations[$field][$locale] ?? $translations[$field]['uz'] ?? $this->{$field} ?? '');
    }

    public function setTr(string $field, array $values): void
    {
        $translations = $this->translations ?? [];
        $translations[$field] = array_filter($values, fn ($value) => $value !== null && $value !== '');
        $this->translations = $translations;
    }
}
