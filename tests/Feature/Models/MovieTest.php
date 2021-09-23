<?php

use Astrotomic\PhpunitAssertions\ArrayAssertions;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Movie;
use Carbon\CarbonInterval;
use PHPUnit\Framework\Assert;
use Spatie\Enum\Phpunit\EnumAssertions;

it('maps data from tmdb', function (): void {
    $movie = new Movie(['id' => 335983]);
    $movie->updateFromTmdb();

    Assert::assertSame(335983, $movie->id);
    Assert::assertSame('Venom', $movie->original_title);
    Assert::assertFalse($movie->adult);
    Assert::assertFalse($movie->video);
    Assert::assertSame('/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg', $movie->backdrop_path);
    Assert::assertSame('/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg', $movie->poster_path);
    Assert::assertSame(116000000, $movie->budget);
    Assert::assertSame(855013954, $movie->revenue);
    Assert::assertSame('http://www.venom.movie/site/', $movie->homepage);
    Assert::assertSame('tt1270797', $movie->imdb_id);
    Assert::assertSame('en', $movie->original_language);
    Assert::assertSame(532.923, $movie->popularity);
    ArrayAssertions::assertEquals(['US', 'CN'], $movie->production_countries);
    Assert::assertTrue($movie->release_date?->isSameDay('2018-09-28'));
    Assert::assertSame(112, $movie->runtime);
    EnumAssertions::assertSameEnum(MovieStatus::RELEASED(), $movie->status);

    Assert::assertCount(2, $movie->genres);
    ArrayAssertions::assertEquals([28, 878], $movie->genres->pluck('id')->all());
});

it('movie provides a poster', function (): void {
    $movie = Movie::query()->find(335983);

    expect($movie->poster())
        ->toBeInstanceOf(Poster::class)
        ->url()->toBeUrl('https://image.tmdb.org/t/p/w780/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
});

it('movie provides a backdrop', function (): void {
    $movie = Movie::query()->find(335983);

    expect($movie->backdrop())
        ->toBeInstanceOf(Backdrop::class)
        ->url()->toBeUrl('https://image.tmdb.org/t/p/w1280/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
});

it('movie provides runtime', function (): void {
    $movie = Movie::query()->find(335983);

    expect($movie->runtime())
        ->toBeInstanceOf(CarbonInterval::class)
        ->totalMinutes->toBe(112);
});

it('translates to undefined language on the fly', function (): void {
    $movie = Movie::query()->find(335983);

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(335983)
        ->translate('tagline', 'de')->toBe('Die Welt hat genug Superhelden.')
        ->translate('tagline', 'en')->toBe('The world has enough Superheroes.');
});

it('does not translate empty language', function (): void {
    $movie = Movie::query()->find(335983);
    $movie->setTranslation('tagline', 'en', null)->save();

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(335983)
        ->translate('tagline', 'de')->toBe('Die Welt hat genug Superhelden.')
        ->translate('tagline', 'en')->toBeNull();
});

it('does return fallback for empty language', function (): void {
    app()->setLocale('en');
    $movie = Movie::query()->find(335983);
    $movie->setTranslation('tagline', 'de', null)->save();

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(335983)
        ->translate('tagline', 'de', true)->toBe('The world has enough Superheroes.')
        ->translate('tagline', 'en', true)->toBe('The world has enough Superheroes.');
});

it('does return fallback for unknown language', function (): void {
    app()->setLocale('en');
    $movie = Movie::query()->find(335983);

    expect($movie)
        ->toBeInstanceOf(Movie::class)
        ->exists->toBeTrue()
        ->wasRecentlyCreated->toBeTrue()
        ->id->toBe(335983)
        ->translate('tagline', 'en', true)->toBe('The world has enough Superheroes.')
        ->translate('tagline', 'foo', true)->toBe('The world has enough Superheroes.');
});
