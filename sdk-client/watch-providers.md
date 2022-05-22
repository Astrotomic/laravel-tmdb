# Watch Providers

## Get Movie Providers

```php
use Astrotomic\Tmdb\Facades\Tmdb;

$watchProviders = Tmdb::client()->watchProviders()->getMovieProviders();
```

{% swagger method="get" path="/watch/providers/movie" baseUrl="https://api.themoviedb.org/3" summary="Returns a list of the watch provider (OTT/streaming) data we have available for movies." %}
{% swagger-description %}


[https://developers.themoviedb.org/3/watch-providers/get-movie-providers](https://developers.themoviedb.org/3/watch-providers/get-movie-providers)


{% endswagger-description %}
{% endswagger %}

## Get TV Providers

```php
use Astrotomic\Tmdb\Facades\Tmdb;

$watchProviders = Tmdb::client()->watchProviders()->getTvProviders();
```

{% swagger method="get" path="/watch/providers/tv" baseUrl="https://api.themoviedb.org/3" summary="Returns a list of the watch provider (OTT/streaming) data we have available for TV series." %}
{% swagger-description %}


[https://developers.themoviedb.org/3/watch-providers/get-tv-providers](https://developers.themoviedb.org/3/watch-providers/get-tv-providers)


{% endswagger-description %}
{% endswagger %}
