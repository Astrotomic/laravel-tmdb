<?php

namespace Astrotomic\Tmdb\Models\Concerns;

use Spatie\Translatable\HasTranslations as SpatieHasTranslations;

trait HasTranslations
{
    use SpatieHasTranslations;

    public function translate(string $key, string $locale = '', bool $useFallbackLocale = false): ?string
    {
        $locale = $locale ?: $this->getLocale();

        $locales = array_keys(
            json_decode($this->getAttributes()[$key] ?? '' ?: '[]', true) ?: []
        );

        if (! in_array($locale, $locales, true)) {
            $this->updateFromTmdb($locale);
        }

        return $this->getTranslation($key, $locale, $useFallbackLocale) ?: null;
    }
}
