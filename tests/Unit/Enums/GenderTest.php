<?php

use Astrotomic\Tmdb\Enums\Gender;

it('instantiates enum from value', function ($value) {
    expect(Gender::from($value))->toBeInstanceOf(Gender::class);
})->with([0, 1, 2, 3]);
