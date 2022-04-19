<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\TvSeason;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method TvSeason newModelInstance(array $attributes = [])
 * @method TvSeason|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method TvSeason|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class TvSeasonBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Tv */
    protected $model;
}
