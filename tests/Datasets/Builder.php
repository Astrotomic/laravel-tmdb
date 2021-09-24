<?php

use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Astrotomic\Tmdb\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

dataset('Builder::find', function (): Generator {
    $set = [
        Movie::class => 335983,
        MovieGenre::class => 35,
        Person::class => 561,
        Credit::class => '5a30d4a40e0a264cbe180b27',
    ];

    foreach ($set as $model => $id) {
        yield $model::table() => [$model, $id];
    }
});

dataset('Builder::findMany', function (): Generator {
    $set = [
        Movie::class => [335983, 575788],
        MovieGenre::class => [35, 99],
        Person::class => [561, 10393],
        Credit::class => ['5a30d4a40e0a264cbe180b27', '5bb637c10e0a2633a7011036'],
    ];

    foreach ($set as $model => $ids) {
        yield $model::table().':array' => [$model, $ids];
        yield $model::table().':collection' => [$model, new Collection($ids)];
        yield $model::table().':fluent' => [$model, new Fluent($ids)];
    }
});

dataset('Builder::findMany@incomplete', function (): Generator {
    $set = [
        Movie::class => [335983, 0],
        MovieGenre::class => [35, 0],
        Person::class => [561, 0],
        Credit::class => ['5a30d4a40e0a264cbe180b27', ''],
    ];

    foreach ($set as $model => $ids) {
        yield $model::table().':array' => [$model, $ids];
        yield $model::table().':collection' => [$model, new Collection($ids)];
        yield $model::table().':fluent' => [$model, new Fluent($ids)];
    }
});

dataset('Builder::findMany@empty', function (): Generator {
    $set = [
        Movie::class => [],
        MovieGenre::class => [],
        Person::class => [],
        Credit::class => [],
    ];

    foreach ($set as $model => $ids) {
        yield $model::table().':array' => [$model, $ids];
        yield $model::table().':collection' => [$model, new Collection($ids)];
        yield $model::table().':fluent' => [$model, new Fluent($ids)];
    }
});
