<?php

use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('returns null when not found', function (): void {
    $genre = MovieGenre::query()->find(0);

    expect($genre)->toBeNull();
});

it('creates movie genre from tmdb', function (): void {
    $genre = MovieGenre::query()->find(35);

    expect($genre)->toBeCreatedModel(MovieGenre::class, 35);
});

it('finds movie genre in database', function (): void {
    MovieGenre::query()->find(35);
    $genre = MovieGenre::query()->find(35);

    expect($genre)->toBeRetrievedModel(MovieGenre::class, 35);
});

it('delegates to findMany', function (): void {
    $genres = MovieGenre::query()->find([35, 99]);

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->each->toBeCreatedModel(MovieGenre::class);
});
