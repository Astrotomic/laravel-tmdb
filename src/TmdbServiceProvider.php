<?php

namespace Astrotomic\Tmdb;

use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\Person;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class TmdbServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Relation::morphMap(collect([Movie::class, Person::class])->keyBy(
            fn (string $model): string => Str::of($model)
                ->classBasename()
                ->singular()
                ->snake()
                ->prepend('tmdb.')
        )->all());
    }
}
