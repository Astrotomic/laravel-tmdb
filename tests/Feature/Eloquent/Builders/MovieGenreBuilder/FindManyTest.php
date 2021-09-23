<?php

use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('creates multiple movie genres from tmdb', function (): void {
    $genres = MovieGenre::query()->findMany([35, 99]);

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->each->toBeCreatedModel(MovieGenre::class);
});

it('creates movie genre from tmdb and ignores not found', function (): void {
    $genres = MovieGenre::query()->findMany([35, 0]);

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(1)
        ->each->toBeCreatedModel(MovieGenre::class, 35);
});

it('creates movie genre from tmdb and finds movie genre in database', function (): void {
    MovieGenre::query()->find(35);
    $genres = MovieGenre::query()->findMany([35, 99]);

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find(35)->toBeRetrievedModel(MovieGenre::class, 35)
        ->find(99)->toBeCreatedModel(MovieGenre::class, 99);
});

it('finds multiple movie genres in database', function (): void {
    MovieGenre::query()->find(35);
    MovieGenre::query()->find(99);
    $genres = MovieGenre::query()->findMany([35, 99]);

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find(35)->toBeRetrievedModel(MovieGenre::class, 35)
        ->find(99)->toBeRetrievedModel(MovieGenre::class, 99);
});

it('returns empty collection without ids', function (): void {
    $movies = Movie::query()->findMany([]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(0);
});
