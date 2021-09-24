<?php

use Astrotomic\Tmdb\Enums\CreditType;

it('instantiates enum from value', function ($value) {
    expect(CreditType::from($value))->toBeInstanceOf(CreditType::class);
})->with(['cast', 'crew']);
