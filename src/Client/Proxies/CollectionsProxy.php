<?php

namespace Astrotomic\Tmdb\Client\Proxies;

use Astrotomic\Tmdb\Client\DTO\Collection;
use Astrotomic\Tmdb\Client\Requests\Collections\GetCollectionDetailsRequest;

class CollectionsProxy extends Proxy
{
    public function getDetails(int $collectionId): Collection
    {
        return $this->connector->send(
            new GetCollectionDetailsRequest($collectionId)
        )->dto();
    }
}
