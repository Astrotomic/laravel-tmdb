<?php

namespace Astrotomic\Tmdb\Eloquent\Builders;

use Astrotomic\Tmdb\Enums\CreditType;

/**
 * @method \Astrotomic\Tmdb\Models\Credit newModelInstance(array $attributes = [])
 * @method \Astrotomic\Tmdb\Models\Credit|\Illuminate\Database\Eloquent\Collection|null find(string|string[]|\Illuminate\Contracts\Support\Arrayable $id, array $columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Collection findMany(string[]|\Illuminate\Contracts\Support\Arrayable $ids, array $columns = ['*'])
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
     * @param class-string<\Astrotomic\Tmdb\Models\Model> $model
     *
     * @return $this
     */
    public function whereMediaType(string $model): static
    {
        return $this->where('media_type', $model::morphType());
    }
}
