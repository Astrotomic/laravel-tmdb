<?php

namespace Astrotomic\Tmdb\Client\Proxies;

use Astrotomic\Tmdb\Client\DTO\Collections\WatchProviderCollection;
use Astrotomic\Tmdb\Client\Requests\WatchProviders\GetMovieProvidersRequest;
use Astrotomic\Tmdb\Client\Requests\WatchProviders\GetTvProvidersRequest;

class WatchProvidersProxy extends Proxy
{
    public function getMovieProviders(): WatchProviderCollection
    {
        return $this->connector->send(
            new GetMovieProvidersRequest()
        )->dto();
    }

    public function getTvProviders(): WatchProviderCollection
    {
        return $this->connector->send(
            new GetTvProvidersRequest()
        )->dto();
    }
}
