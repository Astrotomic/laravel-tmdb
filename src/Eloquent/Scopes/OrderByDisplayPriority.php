<?php

namespace Astrotomic\Tmdb\Eloquent\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderByDisplayPriority implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->orderBy('display_priority', 'asc');
    }
}
