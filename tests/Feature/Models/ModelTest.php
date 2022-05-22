<?php

use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Astrotomic\Tmdb\Models\Person;
use Astrotomic\Tmdb\Models\TvGenre;
use Astrotomic\Tmdb\Models\WatchProvider;

it('can statically retrieve table name', function (string $model): void {
    expect($model::table())->toBe((new $model())->getTable());
})->with([
    Collection::class,
    Credit::class,
    Movie::class,
    MovieGenre::class,
    Person::class,
    TvGenre::class,
    WatchProvider::class,
]);

it('can statically retrieve morph name', function (string $model): void {
    expect($model::morphType())
        ->toBe((new $model())->getMorphClass())
        ->toStartWith('tmdb.');
})->with([
    Collection::class,
    Credit::class,
    Movie::class,
    MovieGenre::class,
    Person::class,
    TvGenre::class,
    WatchProvider::class,
]);

it('can statically retrieve connection name', function (string $model): void {
    expect($model::connection())
        ->toBe((new $model())->getConnectionName())
        ->toBeNull();

    config()->set('database.connections.tmdb', config('database.connections.sqlite'));

    expect($model::connection())
        ->toBe((new $model())->getConnectionName())
        ->toBe('tmdb');
})->with([
    Collection::class,
    Credit::class,
    Movie::class,
    MovieGenre::class,
    Person::class,
    TvGenre::class,
    WatchProvider::class,
]);

it('can statically retrieve qualified column name', function (string $model): void {
    expect($model::qualifiedColumn('id'))->toBe((new $model())->qualifyColumn('id'));
})->with([
    Collection::class,
    Credit::class,
    Movie::class,
    MovieGenre::class,
    Person::class,
    TvGenre::class,
    WatchProvider::class,
]);
