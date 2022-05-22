<?php

namespace Astrotomic\Tmdb\Client\DTO;

use Astrotomic\Tmdb\Client\DTO\Collections\MovieCollection;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;

class Collection
{
    public function __construct(
        public int $id,
        public string $name,
        public string $overview,
        public ?string $posterPath,
        public ?string $backdropPath,
        public MovieCollection $parts,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new static(
            id: $data['id'],
            name: $data['name'],
            overview: $data['overview'],
            posterPath: $data['poster_path'],
            backdropPath: $data['backdrop_path'],
            parts: MovieCollection::fromArray($data['parts']),
        );
    }

    public function poster(): Poster
    {
        return new Poster(
            $this->posterPath,
            $this->name
        );
    }

    public function backdrop(): Backdrop
    {
        return new Backdrop(
            $this->backdropPath,
            $this->name
        );
    }
}
