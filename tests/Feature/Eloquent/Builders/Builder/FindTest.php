<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('returns null when not found', function (string $class): void {
    $movie = $class::query()->find(0);

    expect($movie)->toBeNull();
})->with('Builder::find');

it('creates movie from tmdb', function (string $class, int|string $id): void {
    $movie = $class::query()->find($id);

    expect($movie)->toBeCreatedModel($class, $id);
})->with('Builder::find');

it('finds movie in database', function (string $class, int|string $id): void {
    $class::query()->find($id);
    $movie = $class::query()->find($id);

    expect($movie)->toBeRetrievedModel($class, $id);
})->with('Builder::find');

it('delegates to findMany', function (string $class, array|Arrayable $ids): void {
    $models = $class::query()->find($ids);

    expect($models)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find($ids[0])->toBeCreatedModel($class, $ids[0])
        ->find($ids[1])->toBeCreatedModel($class, $ids[1]);
})->with('Builder::findMany');
