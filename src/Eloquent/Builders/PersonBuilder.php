<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\Person;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Person newModelInstance(array $attributes = [])
 * @method Person|Collection|null find(int|int[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(int[]|Arrayable $ids, array $columns = ['*'])
 * @method Person|Collection findOrFail(int|int[]|Arrayable $id, array $columns = ['*'])
 */
class PersonBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Person */
    protected $model;
}
