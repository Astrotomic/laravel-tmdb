<?php

namespace Astrotomic\Tmdb;

class Tmdb
{
    protected string $region = 'US';
    protected ?string $language = null;

    public function useRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function useLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function language(): string
    {
        return $this->language ?? app()->getLocale();
    }
}
