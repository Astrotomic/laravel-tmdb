<?php

use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('find: returns null when not found', function (): void {
    $genre = MovieGenre::query()->find(0);

    expect($genre)->toBeNull();
});

it('find: creates movie genre from tmdb', function (): void {
    $genre = MovieGenre::query()->find(35);

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35);
});

it('find: movie genre in database', function (): void {
    MovieGenre::query()->find(35);
    $genre = MovieGenre::query()->find(35);

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeFalse()
        ->id->toBe(35);
});

it('findOrFail: creates movie genre from tmdb', function (): void {
    $genre = MovieGenre::query()->findOrFail(35);

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35);
});

it('findOrFail: throws when not found', function (): void {
    MovieGenre::query()->findOrFail(0);
})->throws(ModelNotFoundException::class);
