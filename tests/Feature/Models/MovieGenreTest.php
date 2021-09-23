<?php

use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use PHPUnit\Framework\Assert;

it('maps data from tmdb', function (): void {
    $genre = new MovieGenre(['id' => 35]);
    $genre->updateFromTmdb();

    Assert::assertSame(35, $genre->id);
    Assert::assertSame('Komödie', $genre->name);
});

it('translates to undefined language on the fly', function (): void {
    $genre = MovieGenre::query()->find(35);

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'de')->toBe('Komödie')
        ->translate('name', 'en')->toBe('Comedy');
});

it('does not translate empty language', function (): void {
    $genre = MovieGenre::query()->find(35);
    $genre->setTranslation('name', 'en', null)->save();

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'de')->toBe('Komödie')
        ->translate('name', 'en')->toBeNull();
});

it('does return fallback for empty language', function (): void {
    app()->setLocale('en');
    $genre = MovieGenre::query()->find(35);
    $genre->setTranslation('name', 'de', null)->save();

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'de', true)->toBe('Comedy')
        ->translate('name', 'en', true)->toBe('Comedy');
});

it('does return fallback for unknown language', function (): void {
    app()->setLocale('en');
    $genre = MovieGenre::query()->find(35);

    expect($genre)
        ->toBeInstanceOf(MovieGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'foo', true)->toBe('Comedy')
        ->translate('name', 'en', true)->toBe('Comedy');
});

it('loads all genres from tmdb', function (): void {
    $genres = MovieGenre::all();

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(19);
});
