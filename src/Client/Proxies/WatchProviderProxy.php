<?php

namespace Astrotomic\Tmdb\Client\Proxies;

use Astrotomic\Tmdb\Client\Requests\WatchProvider\ListAllMovieWatchProvidersRequest;

class WatchProviderProxy extends Proxy
{
    public function allForMovies(): ListAllMovieWatchProvidersRequest
    {
        return $this->connector->request(
            new ListAllMovieWatchProvidersRequest()
        );
    }
}
