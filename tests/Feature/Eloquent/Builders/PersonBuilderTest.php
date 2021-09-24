<?php

use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\Person;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('creates person with movie credits', function (): void {
    $person = Person::query()->with('movie_credits')->find(561);

    expect($person)
        ->toBeCreatedModel(Person::class, 561)
        ->credits->toHaveCount(128)->each->toBeInstanceOf(Credit::class)
        ->movie_credits->toHaveCount(128)->each->toBeInstanceOf(Credit::class);

    expect($person->movie_credits()->whereCreditType(CreditType::CAST())->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Credit::class);

    expect($person->movie_credits()->whereCreditType(CreditType::CREW())->get())
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(125)
        ->each->toBeInstanceOf(Credit::class);

    expect(Movie::query()->count())->toBe(127);
});
