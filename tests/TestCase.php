<?php

namespace Tests;

use Astrotomic\Tmdb\TmdbServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            IdeHelperServiceProvider::class,
            TmdbServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('app.locale', 'de');
        config()->set('app.fallback_locale', 'en');

        config()->set('services.tmdb.token', env('TMDB_TOKEN'));
    }
}
