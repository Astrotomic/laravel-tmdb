<?php

use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Models\Credit;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('retrieves cast credits', function () {
    Credit::query()->findMany(['5a30d4a40e0a264cbe180b27', '5a30d4e10e0a264cc417bef3']); // cast
    Credit::query()->findMany(['5c58670992514157df52656b', '58cb216f9251415e2800488a', '5c0dccfb0e0a2638bc0b4a1e']); // crew

    expect(Credit::query()->whereCreditType(CreditType::CAST())->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find('5a30d4a40e0a264cbe180b27')->toBeRetrievedModel(Credit::class, '5a30d4a40e0a264cbe180b27')
        ->find('5a30d4e10e0a264cc417bef3')->toBeRetrievedModel(Credit::class, '5a30d4e10e0a264cc417bef3');
});

it('retrieves crew credits', function () {
    Credit::query()->findMany(['5a30d4a40e0a264cbe180b27', '5a30d4e10e0a264cc417bef3']); // cast
    Credit::query()->findMany(['5c58670992514157df52656b', '58cb216f9251415e2800488a', '5c0dccfb0e0a2638bc0b4a1e']); // crew

    expect(Credit::query()->whereCreditType(CreditType::CREW())->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(3)
        ->find('5c58670992514157df52656b')->toBeRetrievedModel(Credit::class, '5c58670992514157df52656b')
        ->find('58cb216f9251415e2800488a')->toBeRetrievedModel(Credit::class, '58cb216f9251415e2800488a')
        ->find('5c0dccfb0e0a2638bc0b4a1e')->toBeRetrievedModel(Credit::class, '5c0dccfb0e0a2638bc0b4a1e');
});
