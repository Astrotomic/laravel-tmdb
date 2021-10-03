<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\WatchProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method WatchProvider newModelInstance(array $attributes = [])
 * @method WatchProvider|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method WatchProvider|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class WatchProviderBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\WatchProvider */
    protected $model;
}
