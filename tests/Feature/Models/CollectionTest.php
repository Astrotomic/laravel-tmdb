<?php

use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Movie;

it('loads all movies for collection', function () {
    $movies = Collection::query()->findOrFail(529892)->movies()->all();

    expect($movies)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Movie::class);
});

it('inits collection from movie', function () {
    $movie = Movie::query()->findOrFail(284054);
    $collection = $movie->collection;

    expect($movie)->toBeInstanceOf(Movie::class);
    expect($collection)->toBeInstanceOf(Collection::class);

    expect($collection->movies)
        ->toHaveCount(1)
        ->each->toBeInstanceOf(Movie::class);

    $collection->movies()->all();
    $collection->unsetRelation('movies');

    expect($collection->movies)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Movie::class);
});
