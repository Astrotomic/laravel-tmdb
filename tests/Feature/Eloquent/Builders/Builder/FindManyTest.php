<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('creates multiple models from tmdb', function (string $class, array|Arrayable $ids): void {
    $models = $class::query()->findMany($ids);

    expect($models)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find($ids[0])->toBeModel($class, $ids[0])
        ->find($ids[1])->toBeModel($class, $ids[1]);
})->with('Builder::findMany');

it('creates model from tmdb and ignores not found', function (string $class, array|Arrayable $ids): void {
    $models = $class::query()->findMany($ids);

    expect($models)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(1)
        ->find($ids[0])->toBeModel($class, $ids[0]);
})->with('Builder::findMany@incomplete');

it('creates model from tmdb and finds model in database', function (string $class, array|Arrayable $ids): void {
    $class::query()->find($ids[0]);
    $models = $class::query()->findMany($ids);

    expect($models)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find($ids[0])->toBeModel($class, $ids[0])
        ->find($ids[1])->toBeModel($class, $ids[1]);
})->with('Builder::findMany');

it('finds multiple models in database', function (string $class, array|Arrayable $ids): void {
    $class::query()->find($ids[0]);
    $class::query()->find($ids[1]);
    $models = $class::query()->findMany($ids);

    expect($models)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find($ids[0])->toBeModel($class, $ids[0])
        ->find($ids[1])->toBeModel($class, $ids[1]);
})->with('Builder::findMany');

it('returns empty collection without ids', function (string $class, array|Arrayable $ids): void {
    $models = $class::query()->findMany([]);

    expect($models)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(0);
})->with('Builder::findMany@empty');
