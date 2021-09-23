<?php

use Astrotomic\Tmdb\Images\Poster;

it('generates no url for null path', function (): void {
    $poster = new Poster(null);

    expect($poster->url())->toBeNull();
});

it('generates no url for empty path', function (): void {
    $poster = new Poster('');

    expect($poster->url())->toBeNull();
});

it('generates default size url', function (): void {
    $poster = new Poster('/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');

    expect($poster->url())->toBeUrl('https://image.tmdb.org/t/p/w780/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
});

it('generates specific size url', function (): void {
    $poster = new Poster('/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
    $poster->size(Poster::SIZE_W342);

    expect($poster->url())->toBeUrl('https://image.tmdb.org/t/p/w342/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
});

it('generates original size url', function (): void {
    $poster = new Poster('/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
    $poster->size(Poster::SIZE_ORIGINAL);

    expect($poster->url())->toBeUrl('https://image.tmdb.org/t/p/original/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
});

it('stringifies default size url', function (): void {
    $poster = new Poster('/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');

    expect((string) $poster)->toBeUrl('https://image.tmdb.org/t/p/w780/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg');
});

it('stringifies fallback url', function (): void {
    $poster = new Poster(null, 'Venom');

    expect((string) $poster)->toBeUrl('https://via.placeholder.com/780x1170/9ca3af/ffffff.jpg?text=Venom');
});

it('generates HTML img tag', function (): void {
    $poster = new Poster('/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg', 'Venom');

    expect($poster->toHtml())->toBe(
        '<img src="https://image.tmdb.org/t/p/w780/e8XOhZTizIl4vTzOYqaUWIhI5iC.jpg" alt="Venom" loading="lazy" width="780" height="1170"/>'
    );
});
