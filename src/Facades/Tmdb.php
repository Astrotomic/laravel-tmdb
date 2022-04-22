<?php

namespace Astrotomic\Tmdb\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Astrotomic\Tmdb\Tmdb useRegion(string $region)
 * @method static \Astrotomic\Tmdb\Tmdb useLanguage(string $language)
 * @method static mixed withRegion(string $region, \Closure $callback)
 * @method static mixed withLanguage(string $language, \Closure $callback)
 * @method static string region()
 * @method static string language()
 */
class Tmdb extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Astrotomic\Tmdb\Tmdb::class;
    }
}
