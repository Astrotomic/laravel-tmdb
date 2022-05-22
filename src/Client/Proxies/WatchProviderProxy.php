<?php

namespace Astrotomic\Tmdb\Client\Proxies;

use Astrotomic\Tmdb\Client\DTO\Collections\WatchProviderCollection;
use Astrotomic\Tmdb\Client\Requests\WatchProvider\ListAllMovieWatchProvidersRequest;

class WatchProviderProxy extends Proxy
{
    public function getMovieProviders(): WatchProviderCollection
    {
        return $this->connector->send(
            new ListAllMovieWatchProvidersRequest()
        )->dto();
    }
}
