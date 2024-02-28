<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Models\Credit;
use Astrotomic\Tmdb\Models\Model;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * @method Credit newModelInstance(array $attributes = [])
 * @method Credit|Collection|null find(string|string[]|Arrayable $id, array $columns = ['*'])
 * @method Collection findMany(string[]|Arrayable $ids, array $columns = ['*'])
 * @method Credit|Collection findOrFail(string|string[]|Arrayable $id, array $columns = ['*'])
 */
class CreditBuilder extends Builder
{
    /** @var \Astrotomic\Tmdb\Models\Credit */
    protected $model;

    public function whereCreditType(CreditType $type): static
    {
        return $this->where('credit_type', $type);
    }

    /**
     * @param  class-string<\Astrotomic\Tmdb\Models\Model>  $model
     * @return $this
     */
    public function whereMediaType(string $model): static
    {
        if (! is_subclass_of($model, Model::class)) {
            throw new InvalidArgumentException();
        }

        return $this->where('media_type', $model::morphType());
    }
}
