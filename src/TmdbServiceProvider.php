<?php

namespace Astrotomic\Tmdb;

use Astrotomic\Tmdb\Client\TmdbConnector;
use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Astrotomic\Tmdb\Models\Person;
use Astrotomic\Tmdb\Models\TvGenre;
use Astrotomic\Tmdb\Models\WatchProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class TmdbServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Tmdb::class);

        $this->app->singleton(TmdbConnector::class);
    }

    public function boot(): void
    {
        Relation::morphMap(
            collect([
                Collection::class,
                Credit::class,
                Movie::class,
                MovieGenre::class,
                Person::class,
                TvGenre::class,
                WatchProvider::class,
            ])
                ->keyBy(
                    fn (string $model): string => Str::of($model)
                        ->classBasename()
                        ->singular()
                        ->snake()
                        ->prepend('tmdb.')
                )
                ->all()
        );

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'tmdb-migrations');
    }
}
