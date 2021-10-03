<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @method Collection newModelInstance(array $attributes = [])
 * @method Collection|EloquentCollection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method EloquentCollection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method Collection|EloquentCollection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class CollectionBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Collection */
    protected $model;
}
