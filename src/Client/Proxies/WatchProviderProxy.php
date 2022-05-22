<?php

namespace Astrotomic\Tmdb\Client\Proxies;

use Astrotomic\Tmdb\Client\Requests\ListMovieWatchProvidersRequest;

class WatchProviderProxy extends Proxy
{
    public function allForMovies(): ListMovieWatchProvidersRequest
    {
        return (new ListMovieWatchProvidersRequest())->setConnector($this->connector);
    }
}
