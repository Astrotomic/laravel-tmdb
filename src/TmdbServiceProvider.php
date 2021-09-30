<?php

namespace Astrotomic\Tmdb;

use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Astrotomic\Tmdb\Models\Person;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class TmdbServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Tmdb::class);
    }

    public function boot(): void
    {
        Relation::morphMap(collect([Movie::class, Person::class, Credit::class, MovieGenre::class])->keyBy(
            fn (string $model): string => Str::of($model)
                ->classBasename()
                ->singular()
                ->snake()
                ->prepend('tmdb.')
        )->all());

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'tmdb-migrations');
    }
}
