<?php

use Astrotomic\Tmdb\Images\Logo;

it('generates no url for null path', function (): void {
    $logo = new Logo(null);

    expect($logo->url())->toBeNull();
});

it('generates no url for empty path', function (): void {
    $logo = new Logo('');

    expect($logo->url())->toBeNull();
});

it('generates default size url', function (): void {
    $logo = new Logo('/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');

    expect($logo->url())->toBeUrl('https://image.tmdb.org/t/p/w92/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');
});

it('generates specific size url', function (): void {
    $logo = new Logo('/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');
    $logo->size(Logo::SIZE_W300);

    expect($logo->url())->toBeUrl('https://image.tmdb.org/t/p/w300/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');
});

it('generates original size url', function (): void {
    $logo = new Logo('/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');
    $logo->size(Logo::SIZE_ORIGINAL);

    expect($logo->url())->toBeUrl('https://image.tmdb.org/t/p/original/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');
});

it('generates fallback url', function (): void {
    $logo = new Logo(null, 'Netflix');

    expect($logo->fallback())->toBeUrl('https://via.placeholder.com/92x92/9ca3af/ffffff.jpg?text=Netflix');
});

it('generates original size fallback url', function (): void {
    $logo = new Logo(null);
    $logo->size(Logo::SIZE_ORIGINAL);

    expect($logo->fallback())->toBeUrl('https://via.placeholder.com/92x92/9ca3af/ffffff.jpg?text=');
});

it('stringifies default size url', function (): void {
    $logo = new Logo('/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');

    expect((string) $logo)->toBeUrl('https://image.tmdb.org/t/p/w92/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg');
});

it('stringifies fallback url', function (): void {
    $logo = new Logo(null, 'Netflix');

    expect((string) $logo)->toBeUrl('https://via.placeholder.com/92x92/9ca3af/ffffff.jpg?text=Netflix');
});

it('stringifies original size fallback url', function (): void {
    $logo = new Logo(null);
    $logo->size(Logo::SIZE_ORIGINAL);

    expect((string) $logo)->toBeUrl('https://via.placeholder.com/92x92/9ca3af/ffffff.jpg?text=');
});

it('generates HTML img tag', function (): void {
    $logo = new Logo('/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg', 'Netflix');

    expect($logo->toHtml())->toBe(
        '<img src="https://image.tmdb.org/t/p/w92/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg" alt="Netflix" loading="lazy" width="92" height="92"/>'
    );
});
