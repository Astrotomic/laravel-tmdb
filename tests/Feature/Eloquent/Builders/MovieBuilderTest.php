<?php

use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;

it('creates movie with genres', function (): void {
    $movie = Movie::query()->with('genres')->find(335983);

    expect($movie)
        ->toBeCreatedModel(Movie::class, 335983)
        ->genres->toHaveCount(2)->each->toBeInstanceOf(MovieGenre::class);
});

it('creates movie with cast', function (): void {
    $movie = Movie::query()->with('cast')->find(335983);

    expect($movie)
        ->toBeCreatedModel(Movie::class, 335983)
        ->credits->toHaveCount(58)->each->toBeInstanceOf(Credit::class)
        ->cast->toHaveCount(58)->each->toBeInstanceOf(Credit::class)
        ->crew->toBeEmpty();
});

it('creates movie with crew', function (): void {
    $movie = Movie::query()->with('crew')->find(335983);

    expect($movie)
        ->toBeCreatedModel(Movie::class, 335983)
        ->credits->toHaveCount(63)->each->toBeInstanceOf(Credit::class)
        ->cast->toBeEmpty()
        ->crew->toHaveCount(63)->each->toBeInstanceOf(Credit::class);
});

it('creates movie with credits', function (): void {
    $movie = Movie::query()->with('credits')->find(335983);

    expect($movie)
        ->toBeCreatedModel(Movie::class, 335983)
        ->credits->toHaveCount(121)->each->toBeInstanceOf(Credit::class)
        ->cast->toHaveCount(58)->each->toBeInstanceOf(Credit::class)
        ->crew->toHaveCount(63)->each->toBeInstanceOf(Credit::class);
});
