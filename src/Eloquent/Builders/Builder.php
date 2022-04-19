<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Models\Model;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Model|Collection findOrFail(int|int[]|string|string[]|Arrayable $id, string[] $columns = ['*'])
 */
abstract class Builder extends EloquentBuilder
{
    /**
     * @param int|int[]|string|string[]|\Illuminate\Contracts\Support\Arrayable $id
     * @param string[] $columns
     *
     * @return \Astrotomic\Tmdb\Models\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function find($id, $columns = ['*']): Model|Collection|null
    {
        if (is_array($id) || $id instanceof Arrayable) {
            return $this->findMany($id, $columns);
        }

        $model = $this->whereKey($id)->first($columns);

        if ($model instanceof Model) {
            return $model;
        }

        return $this->createFromTmdb($id);
    }

    /**
     * @param int[]|string[]|\Illuminate\Contracts\Support\Arrayable $ids
     * @param string[] $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany($ids, $columns = ['*']): Collection
    {
        $ids = array_unique($ids instanceof Arrayable ? $ids->toArray() : $ids);

        if (empty($ids)) {
            return $this->model->newCollection();
        }

        $models = $this->whereKey($ids)->get($columns);

        if ($models->count() === count($ids)) {
            return $models;
        }

        collect($ids)
            ->reject(fn (int|string $id): bool => $models->contains($id))
            ->map(fn (int|string $id): ?Model => $this->createFromTmdb($id));

        return $this->whereKey($ids)->get($columns);
    }

    protected function createFromTmdb(int|string $id): ?Model
    {
        /** @var static $query */
        $query = $this->getModel()->newQuery();

        /** @var \Astrotomic\Tmdb\Models\Model $model */
        $model = $query->firstOrNew(['id' => $id]);

        if (! $model->updateFromTmdb(with: array_keys($this->getEagerLoads()))) {
            return null;
        }

        return $model;
    }
}
