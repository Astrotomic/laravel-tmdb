<?php

use Astrotomic\Tmdb\Models\Movie;
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
