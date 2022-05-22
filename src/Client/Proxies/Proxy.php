<?php

namespace Astrotomic\Tmdb\Client\Proxies;

use Astrotomic\Tmdb\Client\TmdbConnector;

abstract class Proxy
{
    public function __construct(protected TmdbConnector $connector)
    {
    }
}
