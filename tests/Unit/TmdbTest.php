<?php

use Astrotomic\Tmdb\Tmdb;

it('can use a given language', function (): void {
    $tmdb = new Tmdb();

    expect($tmdb->useLanguage('de'))
        ->language()->toBe('de');
});

it('can use a given region', function (): void {
    $tmdb = new Tmdb();

    expect($tmdb->useRegion('DE'))
        ->region()->toBe('DE');
});
