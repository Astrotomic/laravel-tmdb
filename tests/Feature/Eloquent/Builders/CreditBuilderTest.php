<?php

use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('retrieves cast credits', function () {
    Credit::query()->findMany(['5a30d4a40e0a264cbe180b27', '5a30d4e10e0a264cc417bef3']); // cast
    Credit::query()->findMany(['5c58670992514157df52656b', '58cb216f9251415e2800488a', '5c0dccfb0e0a2638bc0b4a1e']); // crew

    expect(Credit::query()->whereCreditType(CreditType::CAST)->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find('5a30d4a40e0a264cbe180b27')->toBeModel(Credit::class, '5a30d4a40e0a264cbe180b27')
        ->find('5a30d4e10e0a264cc417bef3')->toBeModel(Credit::class, '5a30d4e10e0a264cc417bef3');
});

it('retrieves crew credits', function () {
    Credit::query()->findMany(['5a30d4a40e0a264cbe180b27', '5a30d4e10e0a264cc417bef3']); // cast
    Credit::query()->findMany(['5c58670992514157df52656b', '58cb216f9251415e2800488a', '5c0dccfb0e0a2638bc0b4a1e']); // crew

    expect(Credit::query()->whereCreditType(CreditType::CREW)->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(3)
        ->find('5c58670992514157df52656b')->toBeModel(Credit::class, '5c58670992514157df52656b')
        ->find('58cb216f9251415e2800488a')->toBeModel(Credit::class, '58cb216f9251415e2800488a')
        ->find('5c0dccfb0e0a2638bc0b4a1e')->toBeModel(Credit::class, '5c0dccfb0e0a2638bc0b4a1e');
});

it('retrieves movie credits', function () {
    Credit::query()->findMany(['5a30d4e10e0a264cc417bef3', '5c58670992514157df52656b', '5c0dccfb0e0a2638bc0b4a1e']);
    Credit::query()->find('5a30d4a40e0a264cbe180b27')->update(['media_type' => 'tv']);
    Credit::query()->find('58cb216f9251415e2800488a')->update(['media_type' => 'tv']);

    expect(Credit::query()->whereMediaType(Movie::class)->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(3)
        ->find('5a30d4e10e0a264cc417bef3')->toBeModel(Credit::class, '5a30d4e10e0a264cc417bef3')
        ->find('5c58670992514157df52656b')->toBeModel(Credit::class, '5c58670992514157df52656b')
        ->find('5c0dccfb0e0a2638bc0b4a1e')->toBeModel(Credit::class, '5c0dccfb0e0a2638bc0b4a1e');
});

it('throws exception when media type is not a model', function () {
    Credit::query()->whereMediaType('movie')->get();
})->throws(InvalidArgumentException::class);
