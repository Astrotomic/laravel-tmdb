<?php

use Astrotomic\Tmdb\Requests\GetMovieDetails;
use Illuminate\Http\Client\RequestException;

it('responds with movie data', function (): void {
    $data = GetMovieDetails::request(335983)->send()->json();

    expect($data)
        ->toBeArray()
        ->id->toBe(335983);
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
