![](.github/banner.png)

# Laravel TMDB

[![Latest Version](http://img.shields.io/packagist/v/astrotomic/laravel-tmdb.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/laravel-tmdb)
[![MIT License](https://img.shields.io/github/license/Astrotomic/laravel-tmdb.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/laravel-tmdb/blob/master/LICENSE)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://forest.astrotomic.info)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://larabelles.com)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-tmdb/pest?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/laravel-tmdb/actions?query=workflow%3Apest)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-tmdb/phpcs?style=flat-square&logoColor=white&logo=github&label=PHP+CS)](https://github.com/Astrotomic/laravel-tmdb/actions?query=workflow%3Aphpcs)
[![Total Downloads](https://img.shields.io/packagist/dt/astrotomic/laravel-tmdb.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/astrotomic/laravel-tmdb)

## Installation

```bash
composer require astrotomic/laravel-tmdb
php artisan vendor:publish --tag=tmdb-migrations
```

## Configuration

Add your [TMDB API v4 Token](https://www.themoviedb.org/settings/api) to the `config/services.php` file.

**config/services.php**
```php
return [
    // ...
    
    'tmdb' => [
        'token' => env('TMDB_TOKEN'),
    ],

    // ...
];
```

## Usage

### Models

### Images

### Requests

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Astrotomic/.github/blob/master/CONTRIBUTING.md) for details. You could also be interested in [CODE OF CONDUCT](https://github.com/Astrotomic/.github/blob/master/CODE_OF_CONDUCT.md).

### Security

If you discover any security related issues, please check [SECURITY](https://github.com/Astrotomic/.github/blob/master/SECURITY.md) for steps to report it.

## Credits

- [Tom Witkowski](https://github.com/Gummibeer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

You're free to use this package, but if it makes it to your production environment I would highly appreciate you buying the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to [plant trees](https://www.bbc.co.uk/news/science-environment-48870920). If you contribute to my forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at [ecologi.com/astrotomic](https://forest.astrotomic.info)

Read more about Treeware at [treeware.earth](https://treeware.earth)
