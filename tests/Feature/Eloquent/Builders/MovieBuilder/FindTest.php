<?php

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('returns null when not found', function (): void {
    $movie = Movie::query()->find(0);

    expect($movie)->toBeNull();
});

it('creates movie from tmdb', function (): void {
    $movie = Movie::query()->find(335983);

    expect($movie)->toBeCreatedModel(Movie::class, 335983);
});

it('finds movie in database', function (): void {
    Movie::query()->find(335983);
    $movie = Movie::query()->find(335983);

    expect($movie)->toBeRetrievedModel(Movie::class, 335983);
});

it('delegates to findMany', function (): void {
    $movies = Movie::query()->find([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->each->toBeCreatedModel(Movie::class);
});
