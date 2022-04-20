<?php

namespace Astrotomic\Tmdb\Models\Concerns;

use Spatie\Translatable\HasTranslations as SpatieHasTranslations;

trait HasTranslations
{
    use SpatieHasTranslations;

    public function translate(string $key, string $locale = '', bool $useFallbackLocale = false): ?string
    {
        $locale = $locale ?: $this->getLocale();

        $locales = array_keys($this->getTranslations($key));

        if (! in_array($locale, $locales, true)) {
            $this->updateFromTmdb($locale);
        }

        return $this->getTranslation($key, $locale, $useFallbackLocale) ?: null;
    }

    public function getTranslations(string $key = null, array $allowedLocales = null): array
    {
        if ($key !== null) {
            $this->guardAgainstNonTranslatableAttribute($key);

            return json_decode($this->getAttributes()[$key] ?? null ?: '[]', true) ?: [];
        }

        return array_reduce($this->getTranslatableAttributes(), function ($result, $item) use ($allowedLocales) {
            $result[$item] = $this->getTranslations($item, $allowedLocales);

            return $result;
        });
    }

    public function getTranslatedLocales(string $key): array
    {
        return array_keys(array_filter($this->getTranslations($key)));
    }
}
