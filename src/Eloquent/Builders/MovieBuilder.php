<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

/**
 * @method \Astrotomic\Tmdb\Models\Movie newModelInstance(array $attributes = [])
 * @method \Astrotomic\Tmdb\Models\Movie|\Illuminate\Database\Eloquent\Collection|null find(int|int[]|\Illuminate\Contracts\Support\Arrayable $id, array $columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Collection findMany(int[]|\Illuminate\Contracts\Support\Arrayable $ids, array $columns = ['*'])
 */
class MovieBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Movie */
    protected $model;
}
