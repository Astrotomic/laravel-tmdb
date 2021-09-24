<?php

use Astrotomic\Tmdb\Requests\GetMovieDetails;
use Illuminate\Http\Client\RequestException;

it('responds with movie data', function (): void {
    $data = GetMovieDetails::request(335983)->send()->json();

    expect($data)
        ->toBeArray()
        ->id->toBe(335983)
        ->tagline->toBe('Die Welt hat genug Superhelden.');
});

it('responds with english movie data', function (): void {
    $data = GetMovieDetails::request(335983)->language('en')->send()->json();

    expect($data)
        ->toBeArray()
        ->id->toBe(335983)
        ->tagline->toBe('The world has enough Superheroes.');
});

it('appends credits', function (): void {
    $data = GetMovieDetails::request(335983)
        ->append(GetMovieDetails::APPEND_CREDITS)
        ->send()
        ->json();

    expect($data)
        ->toBeArray()
        ->id->toBe(335983)
        ->credits->toBeArray();
});

it('fails if not found', function (): void {
    GetMovieDetails::request(0)->send()->json();
})->throws(RequestException::class);

it('can call pending request methods', function (): void {
    $foo = false;

    GetMovieDetails::request(335983)
        ->beforeSending(function () use (&$foo): void {
            $foo = true;
        })
        ->send();

    expect($foo)->toBeTrue();
});

it('throws exception for unknown method', function (): void {
    GetMovieDetails::request(335983)->unknownFooMethodBar();
})->throws(BadMethodCallException::class);
