<?php

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('creates movie from tmdb', function (): void {
    $movie = Movie::query()->findOrFail(335983);

    expect($movie)->toBeCreatedModel(Movie::class, 335983);
});

it('creates multiple movies from tmdb', function (): void {
    $movies = Movie::query()->findOrFail([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->each->toBeCreatedModel(Movie::class);
});

it('throws exception when not found', function (): void {
    Movie::query()->findOrFail(0);
})->throws(ModelNotFoundException::class);

it('throws exception when not all found', function (): void {
    Movie::query()->findOrFail([335983, 0]);
})->throws(ModelNotFoundException::class);
