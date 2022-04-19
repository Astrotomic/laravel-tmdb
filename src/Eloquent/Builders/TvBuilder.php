<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\Tv;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Tv newModelInstance(array $attributes = [])
 * @method Tv|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method Tv|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class TvBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Tv */
    protected $model;
}
