# Collections

## Get Collection Details

```php
use Astrotomic\Tmdb\Facades\Tmdb;

$collection = Tmdb::client()->collections()->getDetails(10);
```

{% swagger method="get" path="/collection/{collection_id}" baseUrl="https://api.themoviedb.org/3" summary="Get collection details by id." %}
{% swagger-description %}


[https://developers.themoviedb.org/3/collections/get-collection-details](https://developers.themoviedb.org/3/collections/get-collection-details)


{% endswagger-description %}
{% endswagger %}
