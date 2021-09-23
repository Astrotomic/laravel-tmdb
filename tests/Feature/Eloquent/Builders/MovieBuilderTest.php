<?php

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('find: returns null when not found', function (): void {
    $movie = Movie::query()->find(0);

    expect($movie)->toBeNull();
});

it('find: creates movie from tmdb', function (): void {
    $movie = Movie::query()->find(335983);

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(335983);
});

it('find: movie in database', function (): void {
    Movie::query()->find(335983);
    $movie = Movie::query()->find(335983);

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeFalse()
        ->id->toBe(335983);
});

it('find: delegates to findMany', function (): void {
    $movies = Movie::query()->find([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2);
});

it('findMany: creates movies from tmdb', function (): void {
    $movies = Movie::query()->findMany([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2);
});

it('findMany: creates movie from tmdb and ignores not found', function (): void {
    $movies = Movie::query()->findMany([335983, 0]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(1);
});

it('findMany: creates movie from tmdb and finds movie in database', function (): void {
    Movie::query()->find(335983);
    $movies = Movie::query()->findMany([335983, 575788]);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2);
});

it('findOrFail: creates movie from tmdb', function (): void {
    $movie = Movie::query()->findOrFail(335983);

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(335983);
});

it('findOrFail: throws when not found', function (): void {
    Movie::query()->findOrFail(0);
})->throws(ModelNotFoundException::class);

it('findOrFail: throws when not all found', function (): void {
    Movie::query()->findOrFail([335983, 0]);
})->throws(ModelNotFoundException::class);
