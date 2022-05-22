<?php

namespace Astrotomic\Tmdb\Client\DTO\Collections;

use Astrotomic\Tmdb\Client\DTO\Movie;
use Illuminate\Support\Collection;

class MovieCollection extends Collection
{
    /** @var \Astrotomic\Tmdb\Client\DTO\Movie[] */
    protected $items = [];

    public static function fromArray(array $data): self
    {
        return static::make($data)->map(
            fn (array $item) => Movie::fromArray($item)
        );
    }
}
