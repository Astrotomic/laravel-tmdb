<?php

use Astrotomic\Tmdb\Images\Backdrop;

it('generates no url for null path', function (): void {
    $backdrop = new Backdrop(null);

    expect($backdrop->url())->toBeNull();
});

it('generates no url for empty path', function (): void {
    $backdrop = new Backdrop('');

    expect($backdrop->url())->toBeNull();
});

it('generates default size url', function (): void {
    $backdrop = new Backdrop('/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');

    expect($backdrop->url())->toBeUrl('https://image.tmdb.org/t/p/w1280/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
});

it('generates specific size url', function (): void {
    $backdrop = new Backdrop('/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
    $backdrop->size(Backdrop::SIZE_W300);

    expect($backdrop->url())->toBeUrl('https://image.tmdb.org/t/p/w300/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
});

it('generates original size url', function (): void {
    $backdrop = new Backdrop('/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
    $backdrop->size(Backdrop::SIZE_ORIGINAL);

    expect($backdrop->url())->toBeUrl('https://image.tmdb.org/t/p/original/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
});

it('stringifies default size url', function (): void {
    $backdrop = new Backdrop('/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');

    expect((string) $backdrop)->toBeUrl('https://image.tmdb.org/t/p/w1280/VuukZLgaCrho2Ar8Scl9HtV3yD.jpg');
});

it('stringifies fallback url', function (): void {
    $backdrop = new Backdrop(null, 'Venom');

    expect((string) $backdrop)->toBeUrl('https://via.placeholder.com/1280x720/9ca3af/ffffff.jpg?text=Venom');
});

it('stringifies original size fallback url', function (): void {
    $backdrop = new Backdrop(null);
    $backdrop->size(Backdrop::SIZE_ORIGINAL);

    expect((string) $backdrop)->toBeUrl('https://via.placeholder.com/1920x1080/9ca3af/ffffff.jpg?text=');
});
