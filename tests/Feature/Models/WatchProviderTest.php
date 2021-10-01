<?php

use Astrotomic\Tmdb\Models\WatchProvider;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use PHPUnit\Framework\Assert;

it('maps data from tmdb', function (): void {
    $provider = new WatchProvider(['id' => 8]);
    $provider->updateFromTmdb();

    Assert::assertSame(8, $provider->id);
    Assert::assertSame('Netflix', $provider->name);
});

it('loads all watch providers from tmdb', function (): void {
    $providers = WatchProvider::all();

    expect($providers)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(414)
        ->each->toBeInstanceOf(WatchProvider::class);
});
