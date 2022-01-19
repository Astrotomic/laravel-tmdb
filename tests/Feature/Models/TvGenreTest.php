<?php

use Astrotomic\Tmdb\Models\TvGenre;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use PHPUnit\Framework\Assert;

it('maps data from tmdb', function (): void {
    $genre = new TvGenre(['id' => 10762]);
    $genre->updateFromTmdb();

    Assert::assertSame(10762, $genre->id);
    Assert::assertSame('Kids', $genre->name);
});

it('translates to undefined language on the fly', function (): void {
    $genre = TvGenre::query()->find(16);

    expect($genre)
        ->toBeInstanceOf(TvGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'it')->toBe('Commedia')
        ->translate('name', 'en')->toBe('Comedy');
});

it('does not translate empty language', function (): void {
    $genre = TvGenre::query()->find(35);
    $genre->setTranslation('name', 'en', null)->save();

    expect($genre)
        ->toBeInstanceOf(TvGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'it')->toBe('Commedia')
        ->translate('name', 'en')->toBeNull();
});

it('does return fallback for empty language', function (): void {
    app()->setLocale('en');
    $genre = TvGenre::query()->find(35);
    $genre->setTranslation('name', 'it', null)->save();

    expect($genre)
        ->toBeInstanceOf(TvGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'it', true)->toBe('Comedy')
        ->translate('name', 'en', true)->toBe('Comedy');
});

it('does return fallback for unknown language', function (): void {
    app()->setLocale('en');
    $genre = TvGenre::query()->find(35);

    expect($genre)
        ->toBeInstanceOf(TvGenre::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(35)
        ->translate('name', 'foo', true)->toBe('Comedy')
        ->translate('name', 'en', true)->toBe('Comedy');
});

it('loads all genres from tmdb', function (): void {
    $genres = TvGenre::all();

    expect($genres)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(16);
});
