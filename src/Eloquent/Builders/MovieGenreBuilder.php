<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

/**
 * @method \Astrotomic\Tmdb\Models\MovieGenre newModelInstance(array $attributes = [])
 * @method \Astrotomic\Tmdb\Models\MovieGenre|\Illuminate\Database\Eloquent\Collection|null find(int $id, array $columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Collection findMany(int[] $ids, array $columns = ['*'])
 */
class MovieGenreBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\MovieGenre */
    protected $model;
}
