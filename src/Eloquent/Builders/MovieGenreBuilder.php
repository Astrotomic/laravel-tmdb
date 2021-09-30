<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method MovieGenre newModelInstance(array $attributes = [])
 * @method MovieGenre|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method MovieGenre|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class MovieGenreBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\MovieGenre */
    protected $model;
}
