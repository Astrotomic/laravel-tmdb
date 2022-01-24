<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\TvEpisode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method TvEpisode newModelInstance(array $attributes = [])
 * @method TvEpisode|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method TvEpisode|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class TvEpisodeBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Movie */
    protected $model;
}
