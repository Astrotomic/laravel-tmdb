<?php

use Astrotomic\Tmdb\Requests\GetPersonDetails;
use Illuminate\Http\Client\RequestException;

it('responds with person data', function (): void {
    $data = GetPersonDetails::request(561)->send()->json();

    expect($data)
        ->toBeArray()
        ->id->toBe(561);
});

it('appends movie credits', function (): void {
    $data = GetPersonDetails::request(561)
        ->append(GetPersonDetails::APPEND_MOVIE_CREDITS)
        ->send()
        ->json();

    expect($data)
        ->toBeArray()
        ->id->toBe(561)
        ->movie_credits->toBeArray();
});

it('fails if not found', function (): void {
    GetPersonDetails::request(0)->send()->json();
})->throws(RequestException::class);

it('can call pending request methods', function (): void {
    $foo = false;

    GetPersonDetails::request(561)
        ->beforeSending(function () use (&$foo): void {
            $foo = true;
        })
        ->send();

    expect($foo)->toBeTrue();
});

it('throws exception for unknown method', function (): void {
    GetPersonDetails::request(561)->unknownFooMethodBar();
})->throws(BadMethodCallException::class);
