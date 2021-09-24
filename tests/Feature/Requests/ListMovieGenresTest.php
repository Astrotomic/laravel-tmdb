<?php

use Astrotomic\Tmdb\Requests\ListMovieGenres;

it('responds with movie genres', function (): void {
    $data = ListMovieGenres::request()->send()->json();

    expect($data)
        ->toBeArray()
        ->genres->toBeArray()->each->toHaveKeys(['id', 'name']);
});

it('can call pending request methods', function (): void {
    $foo = false;

    ListMovieGenres::request()
        ->beforeSending(function () use (&$foo): void {
            $foo = true;
        })
        ->send();

    expect($foo)->toBeTrue();
});

it('throws exception for unknown method', function (): void {
    ListMovieGenres::request()->unknownFooMethodBar();
})->throws(BadMethodCallException::class);
