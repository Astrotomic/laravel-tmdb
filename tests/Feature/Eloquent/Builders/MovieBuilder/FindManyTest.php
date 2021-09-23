<?php

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('creates multiple movies from tmdb', function (): void {
    $movies = Movie::query()->findMany([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->each->toBeCreatedModel(Movie::class);
});

it('creates movie from tmdb and ignores not found', function (): void {
    $movies = Movie::query()->findMany([335983, 0]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(1)
        ->each->toBeCreatedModel(Movie::class, 335983);
});

it('creates movie from tmdb and finds movie in database', function (): void {
    Movie::query()->find(335983);
    $movies = Movie::query()->findMany([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find(335983)->toBeRetrievedModel(Movie::class, 335983)
        ->find(575788)->toBeCreatedModel(Movie::class, 575788);
});

it('finds multiple movies in database', function (): void {
    Movie::query()->find(335983);
    Movie::query()->find(575788);
    $movies = Movie::query()->findMany([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find(335983)->toBeRetrievedModel(Movie::class, 335983)
        ->find(575788)->toBeRetrievedModel(Movie::class, 575788);
});

it('returns empty collection without ids', function (): void {
    $movies = Movie::query()->findMany([]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(0);
});
