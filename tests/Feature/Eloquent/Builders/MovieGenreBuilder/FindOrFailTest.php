<?php

use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('creates movie genre from tmdb', function (): void {
    $genre = MovieGenre::query()->findOrFail(35);

    expect($genre)->toBeCreatedModel(MovieGenre::class, 35);
});

it('creates multiple movie genres from tmdb', function (): void {
    $genres = MovieGenre::query()->findOrFail([35, 99]);

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->each->toBeCreatedModel(MovieGenre::class);
});

it('throws exception when not found', function (): void {
    MovieGenre::query()->findOrFail(0);
})->throws(ModelNotFoundException::class);

it('throws exception when not all found', function (): void {
    MovieGenre::query()->findOrFail([35, 0]);
})->throws(ModelNotFoundException::class);
