<?php

namespace Astrotomic\Tmdb\Client;

use Astrotomic\Tmdb\Client\Proxies\CollectionsProxy;
use Astrotomic\Tmdb\Client\Proxies\WatchProvidersProxy;
use Astrotomic\Tmdb\Client\Requests\WatchProviders\GetMovieProvidersRequest;
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
        GetMovieProvidersRequest::class,
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

    public function collections(): CollectionsProxy
    {
        return new CollectionsProxy($this);
    }

    public function watchProviders(): WatchProvidersProxy
    {
        return new WatchProvidersProxy($this);
    }
}
