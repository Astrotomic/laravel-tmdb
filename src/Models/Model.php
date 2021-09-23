<?php

namespace Astrotomic\Tmdb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    public $incrementing = false;

    public static function table(): string
    {
        return (new static())->getTable();
    }

    public static function morphType(): string
    {
        return (new static())->getMorphClass();
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
