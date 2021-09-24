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

expect()->extend('toBeCreatedModel', function (?string $model = null, int|string|null $id = null): Expectation {
    $this->toBeInstanceOf(Model::class);
    if ($model !== null) {
        $this->toBeInstanceOf($model);
    }

    $this->exists->toBeTrue();
    $this->wasRecentlyCreated->toBeTrue();

    if ($id !== null) {
        $this->id->toBe($id);
    }

    return $this;
});

expect()->extend('toBeRetrievedModel', function (?string $model = null, int|string|null $id = null): Expectation {
    $this->toBeInstanceOf(Model::class);
    if ($model !== null) {
        $this->toBeInstanceOf($model);
    }

    $this->exists->toBeTrue();
    $this->wasRecentlyCreated->toBeFalse();

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
