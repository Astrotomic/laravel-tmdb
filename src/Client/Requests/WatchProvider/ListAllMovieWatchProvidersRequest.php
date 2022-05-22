<?php

namespace Astrotomic\Tmdb\Client\Requests\WatchProvider;

use Astrotomic\Tmdb\Client\DTO\Collections\WatchProviderCollection;
use Astrotomic\Tmdb\Client\TmdbConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;
use Sammyjo20\Saloon\Traits\Plugins\CastsToDto;

class ListAllMovieWatchProvidersRequest extends SaloonRequest
{
    use CastsToDto;

    protected ?string $connector = TmdbConnector::class;

    protected ?string $method = Saloon::GET;

    public function defineEndpoint(): string
    {
        return '/watch/providers/movie';
    }

    protected function castToDto(SaloonResponse $response): WatchProviderCollection
    {
        return WatchProviderCollection::fromSaloon($response);
    }
}
