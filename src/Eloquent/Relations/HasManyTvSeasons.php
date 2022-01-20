<?php

namespace Astrotomic\Tmdb\Eloquent\Relations;

use Astrotomic\Tmdb\Requests\Tv\Details;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasManyTvSeasons extends HasMany
{
    public function all(array $columns = ['*']): Collection
    {
        $ids = Details::request($this->getParentKey())->send()->json('parts.*.id');

        return $this->query->findMany($ids, $columns);
    }
}
