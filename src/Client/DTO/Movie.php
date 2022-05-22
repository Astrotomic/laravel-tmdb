<?php

namespace Astrotomic\Tmdb\Client\DTO;

use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Carbon\CarbonImmutable;

class Movie
{
    public function __construct(
        public int $id,
        public string $title,
        public string $overview,
        public ?string $posterPath,
        public ?string $backdropPath,
        public array $genreIds,
        public string $originalLanguage,
        public string $originalTitle,
        public CarbonImmutable $releaseDate,
        public float $popularity,
        public float $voteAverage,
        public int $voteCount,
        public bool $adult,
        public bool $video,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new static(
            id: $data['id'],
            title: $data['title'],
            overview: $data['overview'],
            posterPath: $data['poster_path'],
            backdropPath: $data['backdrop_path'],
            genreIds: $data['genre_ids'],
            originalLanguage: $data['original_language'],
            originalTitle: $data['original_title'],
            releaseDate: CarbonImmutable::parse($data['release_date']),
            popularity: $data['popularity'],
            voteAverage: $data['vote_average'],
            voteCount: $data['vote_count'],
            adult: $data['adult'],
            video: $data['video'],
        );
    }

    public function poster(): Poster
    {
        return new Poster(
            $this->posterPath,
            $this->title
        );
    }

    public function backdrop(): Backdrop
    {
        return new Backdrop(
            $this->backdropPath,
            $this->title
        );
    }
}
