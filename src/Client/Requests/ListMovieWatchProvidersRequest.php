<?php

namespace Astrotomic\Tmdb\Client\Requests;

use Astrotomic\Tmdb\Client\TmdbConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class ListMovieWatchProvidersRequest extends SaloonRequest
{
    protected ?string $connector = TmdbConnector::class;

    protected ?string $method = Saloon::GET;

    public function defineEndpoint(): string
    {
        return '/watch/providers/movie';
    }
}
