<?php

use Astrotomic\Tmdb\Requests\GetCreditDetails;
use Illuminate\Http\Client\RequestException;

it('responds with credit data', function (): void {
    $data = GetCreditDetails::request('5a30d4a40e0a264cbe180b27')->send()->json();

    expect($data)
        ->toBeArray()
        ->id->toBe('5a30d4a40e0a264cbe180b27');
});

it('fails if not found', function (): void {
    GetCreditDetails::request('')->send()->json();
})->throws(RequestException::class);

it('can call pending request methods', function (): void {
    $foo = false;

    GetCreditDetails::request('5a30d4a40e0a264cbe180b27')
        ->beforeSending(function () use (&$foo): void {
            $foo = true;
        })
        ->send();

    expect($foo)->toBeTrue();
});

it('throws exception for unknown method', function (): void {
    GetCreditDetails::request('5a30d4a40e0a264cbe180b27')->unknownFooMethodBar();
})->throws(BadMethodCallException::class);
