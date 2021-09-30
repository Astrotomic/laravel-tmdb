<?php

namespace Astrotomic\Tmdb\Models;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    public $incrementing = false;

    public static function table(): string
    {
        return (new static())->getTable();
    }

    public static function morphType(): string
    {
        return (new static())->getMorphClass();
    }

    public static function qualifiedColumn(string $column): string
    {
        return (new static())->qualifyColumn($column);
    }

    public function getConnectionName(): ?string
    {
        $connection = parent::getConnectionName();

        if (is_string($connection)) {
            return $connection;
        }

        if (config()->has('database.connections.tmdb')) {
            return 'tmdb';
        }

        return null;
    }

    abstract public function updateFromTmdb(?string $locale = null, array $with = []): bool;

    abstract public function fillFromTmdb(array $data, ?string $locale = null): static;
}
