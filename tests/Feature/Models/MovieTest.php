<?php

use Astrotomic\PhpunitAssertions\ArrayAssertions;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Enums\WatchProviderType;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\WatchProvider;
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
    ArrayAssertions::assertEquals(['zh', 'en', 'ms'], $movie->spoken_languages);
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

it('movie has no runtime', function (): void {
    $movie = new Movie();

    expect($movie->runtime())->toBeNull();
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

it('loads all recommended movies', function (): void {
    $movies = Movie::query()->findOrFail(335983)->recommendations(null);

    expect($movies)
        ->toHaveCount(40)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/335983/recommendations'))
        ->toHaveCount(2);
});

it('loads first page of recommended movies', function (): void {
    $movies = Movie::query()->findOrFail(335983)->recommendations(20);

    expect($movies)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/335983/recommendations'))
        ->toHaveCount(1);
});

it('loads several pages of similar movies', function (): void {
    $movies = Movie::query()->findOrFail(335983)->similars(60);

    expect($movies)
        ->toHaveCount(55)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/335983/similar'))
        ->toHaveCount(3);
});

it('loads first page of similar movies', function (): void {
    $movies = Movie::query()->findOrFail(335983)->similars(20);

    expect($movies)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/335983/similar'))
        ->toHaveCount(1);
});

it('loads first page of popular movies', function (): void {
    $movies = Movie::popular(20);

    expect($movies)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/popular'))
        ->toHaveCount(1);
});

it('loads several pages of popular movies', function (): void {
    $movies = Movie::popular(60);

    expect($movies)
        ->toHaveCount(60)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/popular'))
        ->toHaveCount(3);
});

it('loads first page of top rated movies', function (): void {
    $movies = Movie::toprated(20);

    expect($movies)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/top_rated'))
        ->toHaveCount(1);
});

it('loads several pages of top rated movies', function (): void {
    $movies = Movie::toprated(60);

    expect($movies)
        ->toHaveCount(60)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/top_rated'))
        ->toHaveCount(3);
});

it('loads first page of upcoming movies', function (): void {
    $movies = Movie::upcoming(20);

    expect($movies)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/upcoming'))
        ->toHaveCount(1);
});

it('loads several pages of upcoming movies', function (): void {
    $movies = Movie::upcoming(60);

    expect($movies)
        ->toHaveCount(58)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('movie/upcoming'))
        ->toHaveCount(3);
});

it('loads all watch providers', function (): void {
    $providers = Movie::query()->findOrFail(335983)->watchProviders();

    expect($providers)
        ->toHaveCount(61)
        ->each->toBeInstanceOf(WatchProvider::class);
});

it('loads german watch providers', function (): void {
    $providers = Movie::query()->findOrFail(335983)->watchProviders('DE');

    expect($providers)
        ->toHaveCount(11)
        ->each->toBeInstanceOf(WatchProvider::class);
});

it('loads buy watch providers', function (): void {
    $providers = Movie::query()->findOrFail(335983)->watchProviders(null, WatchProviderType::BUY());

    expect($providers)
        ->toHaveCount(31)
        ->each->toBeInstanceOf(WatchProvider::class);
});

it('loads rent watch providers', function (): void {
    $providers = Movie::query()->findOrFail(335983)->watchProviders(null, WatchProviderType::RENT());

    expect($providers)
        ->toHaveCount(36)
        ->each->toBeInstanceOf(WatchProvider::class);
});

it('loads flatrate watch providers', function (): void {
    $providers = Movie::query()->findOrFail(335983)->watchProviders(null, WatchProviderType::FLATRATE());

    expect($providers)
        ->toHaveCount(24)
        ->each->toBeInstanceOf(WatchProvider::class);
});

it('loads german flatrate watch providers', function (): void {
    $providers = Movie::query()->findOrFail(335983)->watchProviders('DE', WatchProviderType::FLATRATE());

    expect($providers)
        ->toHaveCount(1)
        ->each->toBeInstanceOf(WatchProvider::class);
});
