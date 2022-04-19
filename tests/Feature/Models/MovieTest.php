<?php

use Astrotomic\PhpunitAssertions\ArrayAssertions;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Enums\WatchProviderType;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Astrotomic\Tmdb\Models\WatchProvider;
use Carbon\CarbonInterval;
use PHPUnit\Framework\Assert;
use Spatie\Enum\Phpunit\EnumAssertions;

it('maps data from tmdb', function (): void {
    $movie = new Movie(['id' => 284054]);
    $movie->updateFromTmdb();

    Assert::assertSame(284054, $movie->id);
    Assert::assertSame('Black Panther', $movie->original_title);
    Assert::assertFalse($movie->adult);
    Assert::assertFalse($movie->video);
    Assert::assertSame('/b6ZJZHUdMEFECvGiDpJjlfUWela.jpg', $movie->backdrop_path);
    Assert::assertSame(200000000, $movie->budget);
    Assert::assertSame(1346739107, $movie->revenue);
    Assert::assertSame('https://marvel.com/movies/movie/224/black_panther', $movie->homepage);
    Assert::assertSame('tt1825683', $movie->imdb_id);
    Assert::assertSame('en', $movie->original_language);
    Assert::assertSame(81.635, $movie->popularity);
    ArrayAssertions::assertEquals(['US'], $movie->production_countries);
    ArrayAssertions::assertEquals(['en', 'ko', 'sw', 'xh'], $movie->spoken_languages);
    Assert::assertTrue($movie->release_date?->isSameDay('2018-02-13'));
    Assert::assertSame(134, $movie->runtime);
    EnumAssertions::assertSameEnum(MovieStatus::RELEASED(), $movie->status);

    Assert::assertSame('/daKUTgrMnsMLFrRv3a7s6yUyXf1.jpg', $movie->poster_path);
    Assert::assertSame('Black Panther', $movie->title);
    Assert::assertSame('Lang lebe der König', $movie->tagline);
    Assert::assertSame('Aufgrund von Bodenschätzen außerirdischen Ursprungs ist das afrikanische Königreich Wakanda unermesslich reich. Nur hier kommt das Vibrationen jeder Art und Stärke absorbierende Mineral Vibranium vor. Den Bewohnern von Wakanda ist sehr daran gelegen, vor den Augen Fremder verborgen zu bleiben.  Reichtum weckt Begehrlichkeiten, und es braucht einen starken Führer, ihn zu verteidigen: Den Black Panther! Die Verantwortung des Black Panther wird vom König Wakandas an den jeweiligen Nachfolger weitergegeben. Und so nimmt T’Challa die Bürde und die Würde des ihm vorbestimmten Schicksals von seinem Vater T’Chaka nach dessen tragischen Tod entgegen.', $movie->overview);

    Assert::assertCount(3, $movie->genres);
    Assert::assertContainsOnly(MovieGenre::class, $movie->genres);
    ArrayAssertions::assertEquals([12, 28, 878], $movie->genres->pluck('id')->all());

    Assert::assertSame(529892, $movie->collection_id);
    Assert::assertInstanceOf(Collection::class, $movie->collection);
    Assert::assertSame(529892, $movie->collection->id);
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
        ->toHaveCount(52)
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

it('loads first page of trending movies', function (): void {
    $movies = Movie::trending(20);

    expect($movies)
        ->toHaveCount(20)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('trending/movie/day'))
        ->toHaveCount(1);
});

it('loads several pages of trending movies', function (): void {
    $movies = Movie::trending(60);

    expect($movies)
        ->toHaveCount(60)
        ->each->toBeInstanceOf(Movie::class);

    expect(requests('trending/movie/day'))
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

it('loads all credits for movie', function () {
    $credits = Movie::query()->findOrFail(335983)->credits()->all();

    expect($credits)
        ->toHaveCount(121)
        ->each->toBeInstanceOf(Credit::class);
});

it('loads all cast credits for movie', function () {
    $credits = Movie::query()->findOrFail(335983)->cast()->all();

    expect($credits)
        ->toHaveCount(58)
        ->each->toBeInstanceOf(Credit::class);
});

it('loads all crew credits for movie', function () {
    $credits = Movie::query()->findOrFail(335983)->crew()->all();

    expect($credits)
        ->toHaveCount(63)
        ->each->toBeInstanceOf(Credit::class);
});
