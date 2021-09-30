<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Movie newModelInstance(array $attributes = [])
 * @method Movie|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method Movie|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class MovieBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Movie */
    protected $model;
}
