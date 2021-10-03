<?php

use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
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

it('collection provides a poster', function (): void {
    $collection = Collection::query()->find(529892);

    expect($collection->poster())
        ->toBeInstanceOf(Poster::class)
        ->url()->toBeUrl('https://image.tmdb.org/t/p/w780/uVnN6KnfDuHiC8rsVsSc7kk0WRD.jpg');
});

it('collection provides a backdrop', function (): void {
    $collection = Collection::query()->find(529892);

    expect($collection->backdrop())
        ->toBeInstanceOf(Backdrop::class)
        ->url()->toBeUrl('https://image.tmdb.org/t/p/w1280/1Jj7Frjjbewb6Q6dl6YXhL3kuvL.jpg');
});
