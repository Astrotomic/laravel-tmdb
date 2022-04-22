<?php

use Astrotomic\Tmdb\Tmdb;

it('can use a given language', function (): void {
    $tmdb = new Tmdb();

    $tmdb->useLanguage('de');

    expect($tmdb->language())->toBe('de');
});

it('can use a given region', function (): void {
    $tmdb = new Tmdb();

    $tmdb->useRegion('DE');

    expect($tmdb->region())->toBe('DE');
});

it('can run a callback with a given language', function (): void {
    $tmdb = new Tmdb();

    $tmdb->useLanguage('en');

    $tmdb->withLanguage('de', fn () => expect($tmdb->language())->toBe('de'));

    expect($tmdb->language())->toBe('en');
});

it('can run a callback with a given region', function (): void {
    $tmdb = new Tmdb();

    $tmdb->useRegion('US');

    $tmdb->withRegion('DE', fn () => expect($tmdb->region())->toBe('DE'));

    expect($tmdb->region())->toBe('US');
});
