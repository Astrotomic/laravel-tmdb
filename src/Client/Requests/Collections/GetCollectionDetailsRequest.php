<?php

namespace Astrotomic\Tmdb\Client\Requests\Collections;

use Astrotomic\Tmdb\Client\DTO\Collection;
use Astrotomic\Tmdb\Client\TmdbConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;
use Sammyjo20\Saloon\Traits\Plugins\CastsToDto;

/**
 * @link https://developers.themoviedb.org/3/collections/get-collection-details
 */
class GetCollectionDetailsRequest extends SaloonRequest
{
    use CastsToDto;

    protected ?string $connector = TmdbConnector::class;

    protected ?string $method = Saloon::GET;

    public function __construct(protected int $collectionId)
    {
    }

    public function defineEndpoint(): string
    {
        return "/collection/{$this->collectionId}";
    }

    protected function castToDto(SaloonResponse $response): Collection
    {
        return Collection::fromArray($response->json());
    }
}
