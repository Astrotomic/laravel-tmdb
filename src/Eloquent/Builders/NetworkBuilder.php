<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\Network;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Network newModelInstance(array $attributes = [])
 * @method Network|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method Network|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class NetworkBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Network */
    protected $model;
}
