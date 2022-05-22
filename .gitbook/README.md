![](../.github/banner.png)

[![Latest Version](http://img.shields.io/packagist/v/astrotomic/laravel-tmdb.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/laravel-tmdb)
[![MIT License](https://img.shields.io/github/license/Astrotomic/laravel-tmdb.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/laravel-tmdb/blob/master/LICENSE)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://forest.astrotomic.info)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://larabelles.com)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-tmdb/pest?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/laravel-tmdb/actions?query=workflow%3Apest)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-tmdb/phpcs?style=flat-square&logoColor=white&logo=github&label=PHP+CS)](https://github.com/Astrotomic/laravel-tmdb/actions?query=workflow%3Aphpcs)

[![Total Downloads](https://img.shields.io/packagist/dt/astrotomic/laravel-tmdb.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/astrotomic/laravel-tmdb)
[![Trees](https://img.shields.io/ecologi/trees/astrotomic?style=flat-square)](https://forest.astrotomic.info)
[![Carbon](https://img.shields.io/ecologi/carbon/astrotomic?style=flat-square)](https://forest.astrotomic.info)

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

After that you can configure your language and region to be used by the package for some of the API requests.
By default we use `app()->getLocale()` for the language and a hardcoded `US` region.
It's recommended to call this in your `AppServiceProvider` but you can call the methods from everywhere in your codecase.
In case you want to run a specific callback with a region or language without changing the globally used ones you can use the `with` methods.
These will set the region or language to teh given one for the callback and automatically restore the old one after running the callback.

```php
use Astrotomic\Tmdb\Facades\Tmdb;

Tmdb::useLanguage('de');
Tmdb::useRegion('DE');

Tmdb::withLanguage('de', fn() => \Astrotomic\Tmdb\Models\Movie::find(335983));
Tmdb::withRegion('DE', fn() => \Astrotomic\Tmdb\Models\Movie::upcoming(20));
```
