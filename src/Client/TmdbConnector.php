<?php

namespace Astrotomic\Tmdb\Client;

use Astrotomic\Tmdb\Client\Proxies\WatchProviderProxy;
use Astrotomic\Tmdb\Client\Requests\WatchProvider\ListAllMovieWatchProvidersRequest;
use Astrotomic\Tmdb\Facades\Tmdb;
use Sammyjo20\Saloon\Http\Auth\TokenAuthenticator;
use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Interfaces\AuthenticatorInterface;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
use Sammyjo20\Saloon\Traits\Plugins\AlwaysThrowsOnErrors;
use Sammyjo20\Saloon\Traits\Plugins\HasTimeout;

class TmdbConnector extends SaloonConnector
{
    use AcceptsJson;
    use HasTimeout;
    use AlwaysThrowsOnErrors;

    protected array $requests = [
        ListAllMovieWatchProvidersRequest::class,
    ];

    public function defineBaseUrl(): string
    {
        return 'https://api.themoviedb.org/3';
    }

    public function defaultAuth(): ?AuthenticatorInterface
    {
        return new TokenAuthenticator(config('services.tmdb.token'));
    }

    public function defaultQuery(): array
    {
        return [
            'language' => Tmdb::language(),
            'region' => Tmdb::region(),
        ];
    }

    public function watchProviders(): WatchProviderProxy
    {
        return new WatchProviderProxy($this);
    }
}
