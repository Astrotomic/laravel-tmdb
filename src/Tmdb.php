<?php

namespace Astrotomic\Tmdb;

use Astrotomic\Tmdb\Client\TmdbConnector;
use Closure;

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

    public function withRegion(string $region, Closure $callback): mixed
    {
        $old = $this->region;

        $this->useRegion($region);

        $return = $callback();

        $this->region = $old;

        return $return ?? $this;
    }

    public function withLanguage(?string $language, Closure $callback): mixed
    {
        $old = $this->language;

        $this->useLanguage($language);

        $return = $callback();

        $this->language = $old;

        return $return ?? $this;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function language(): string
    {
        return $this->language ?? app()->getLocale();
    }

    public function client(): TmdbConnector
    {
        return app()->make(TmdbConnector::class);
    }
}
