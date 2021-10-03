<?php

namespace Astrotomic\Tmdb\Eloquent\Relations;

use Astrotomic\Tmdb\Requests\Collection\Details;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasManyMovies extends HasMany
{
    public function all($columns = ['*']): Collection
    {
        $ids = Details::request($this->getParentKey())->send()->json('parts.*.id');

        return $this->query->findMany($ids, $columns);
    }
}
