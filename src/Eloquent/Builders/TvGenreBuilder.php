<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\TvGenre;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method TvGenre newModelInstance(array $attributes = [])
 * @method TvGenre|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method TvGenre|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class TvGenreBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\TvGenre */
    protected $model;
}
