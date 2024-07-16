<?php

use Astrotomic\PhpunitAssertions\ArrayAssertions;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Assert;

beforeEach(function (): void {
    $this->markTestSkipped('Live HTTP tests are disbaled by default.');

    MovieGenre::all();

    Http::fake(['foo' => 'bar']);
});

it('loads "Black Panther" from API', function (): void {
    $movie = Movie::findOrFail(284054);

    Assert::assertSame(284054, $movie->id);
    Assert::assertSame('Black Panther', $movie->original_title);
    ArrayAssertions::assertEquals(['US'], $movie->production_countries);
    Assert::assertSame(MovieStatus::RELEASED, $movie->status);

    ArrayAssertions::assertEquals([28, 12, 878], $movie->genres->pluck('id')->all());
    Assert::assertEmpty($movie->credits);
    Assert::assertEmpty($movie->cast);
    Assert::assertEmpty($movie->crew);

    dump(requests());
});

it('loads "Black Panther" with cast from API', function (): void {
    $movie = Movie::with('cast')->findOrFail(284054);

    Assert::assertSame(284054, $movie->id);
    Assert::assertSame('Black Panther', $movie->original_title);
    ArrayAssertions::assertEquals(['US'], $movie->production_countries);
    Assert::assertSame(MovieStatus::RELEASED, $movie->status);

    ArrayAssertions::assertEquals([28, 12, 878], $movie->genres->pluck('id')->all());
    Assert::assertCount(77, $movie->credits);
    Assert::assertCount(77, $movie->cast);
    Assert::assertEmpty($movie->crew);

    dump(requests());
});

it('loads "Black Panther" with crew from API', function (): void {
    $movie = Movie::with('crew')->findOrFail(284054);

    Assert::assertSame(284054, $movie->id);
    Assert::assertSame('Black Panther', $movie->original_title);
    ArrayAssertions::assertEquals(['US'], $movie->production_countries);
    Assert::assertSame(MovieStatus::RELEASED, $movie->status);

    ArrayAssertions::assertEquals([28, 12, 878], $movie->genres->pluck('id')->all());
    Assert::assertCount(479, $movie->credits);
    Assert::assertEmpty($movie->cast);
    Assert::assertCount(479, $movie->crew);

    dump(requests());
});

it('loads "Black Panther" with credits from API', function (): void {
    $movie = Movie::with('credits')->findOrFail(284054);

    Assert::assertSame(284054, $movie->id);
    Assert::assertSame('Black Panther', $movie->original_title);
    ArrayAssertions::assertEquals(['US'], $movie->production_countries);
    Assert::assertSame(MovieStatus::RELEASED, $movie->status);

    ArrayAssertions::assertEquals([28, 12, 878], $movie->genres->pluck('id')->all());
    Assert::assertCount(556, $movie->credits);
    Assert::assertCount(77, $movie->cast);
    Assert::assertCount(479, $movie->crew);

    dump(requests());
});
