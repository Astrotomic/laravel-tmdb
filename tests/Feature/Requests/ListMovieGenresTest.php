<?php

use Astrotomic\Tmdb\Requests\MovieGenre\ListAll;

it('responds with movie genres', function (): void {
    $data = ListAll::request()->send()->json();

    expect($data)
        ->toBeArray()
        ->genres->toBeArray()->each->toHaveKeys(['id', 'name']);
});

it('can call pending request methods', function (): void {
    $foo = false;

    ListAll::request()
        ->beforeSending(function () use (&$foo): void {
            $foo = true;
        })
        ->send();

    expect($foo)->toBeTrue();
});

it('throws exception for unknown method', function (): void {
    ListAll::request()->unknownFooMethodBar();
})->throws(BadMethodCallException::class);
