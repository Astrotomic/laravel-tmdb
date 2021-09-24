<?php

use Astrotomic\Tmdb\Enums\MovieStatus;

it('instantiates enum from value', function ($value) {
    expect(MovieStatus::from($value))->toBeInstanceOf(MovieStatus::class);
})->with(['Rumored', 'Planned', 'In Production', 'Post Production', 'Released', 'Canceled']);
