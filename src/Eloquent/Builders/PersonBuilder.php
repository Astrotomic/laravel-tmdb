<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

/**
 * @method \Astrotomic\Tmdb\Models\Person newModelInstance(array $attributes = [])
 * @method \Astrotomic\Tmdb\Models\Person|\Illuminate\Database\Eloquent\Collection|null find(int|int[]|\Illuminate\Contracts\Support\Arrayable $id, array $columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Collection findMany(int[]|\Illuminate\Contracts\Support\Arrayable $ids, array $columns = ['*'])
 */
class PersonBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Person */
    protected $model;
}
