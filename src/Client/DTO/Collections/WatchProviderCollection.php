<?php

namespace Astrotomic\Tmdb\Client\DTO\Collections;

use Astrotomic\Tmdb\Client\DTO\WatchProvider;
use Illuminate\Support\Collection;
use Sammyjo20\Saloon\Http\SaloonResponse;

class WatchProviderCollection extends Collection
{
    /** @var \Astrotomic\Tmdb\Client\DTO\WatchProvider[] */
    protected $items = [];

    public static function fromSaloon(SaloonResponse $response): self
    {
        return static::fromArray(
            $response->json('results')
        );
    }

    public static function fromArray(array $data): self
    {
        return static::make($data)->map(
            fn (array $item) => WatchProvider::fromArray($item)
        );
    }
}
