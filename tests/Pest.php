<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Astrotomic\PhpunitAssertions\UrlAssertions;
use Astrotomic\Tmdb\Models\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Pest\Expectation;

uses(\Tests\Feature\TestCase::class)->in('Feature');
uses(\Tests\TestCase::class)->in('Live');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('assert', function (Closure $assertions): Expectation {
    $assertions($this->value);

    return $this;
});

expect()->extend('toBeUrl', function (string $expected): Expectation {
    UrlAssertions::assertValidLoose($this->value);

    return $this->toBe($expected);
});

expect()->extend('toBeModel', function (?string $model = null, int|string|null $id = null): Expectation {
    $this->toBeInstanceOf(Model::class);
    if ($model !== null) {
        $this->toBeInstanceOf($model);
    }

    $this->exists->toBeTrue();

    if ($id !== null) {
        $this->id->toBe($id);
    }

    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

if (! function_exists('requests')) {
    function requests(?string $pattern = null): Collection
    {
        return Http::recorded()
            ->pluck(0)
            ->map->url()
            ->when(
                $pattern,
                fn (Collection $urls): Collection => $urls->filter(fn (string $url) => str_contains($url, $pattern))
            );
    }
}
