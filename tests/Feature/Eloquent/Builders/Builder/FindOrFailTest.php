<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('creates movie from tmdb', function (string $class, int|string $id): void {
    $model = $class::query()->findOrFail($id);

    expect($model)->toBeCreatedModel($class, $id);
})->with('Builder::find');

it('creates multiple movies from tmdb', function (string $class, array|Arrayable $ids): void {
    $movies = $class::query()->findOrFail($ids);

    expect($movies)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(2)
        ->find($ids[0])->toBeCreatedModel($class, $ids[0])
        ->find($ids[1])->toBeCreatedModel($class, $ids[1]);
})->with('Builder::findMany');

it('throws exception when not found', function (string $class): void {
    $class::query()->findOrFail(0);
})->throws(ModelNotFoundException::class)->with('Builder::find');

it('throws exception when not all found', function (string $class, array|Arrayable $ids): void {
    $class::query()->findOrFail($ids);
})->throws(ModelNotFoundException::class)->with('Builder::findMany@incomplete');
