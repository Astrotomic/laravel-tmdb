<?php

namespace Astrotomic\Tmdb\Models;

use Spatie\Translatable\HasTranslations;

class MovieGenre extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'name', // ToDo: trans
    ];

    protected $casts = [
        'id' => 'int',
    ];

    public array $translatable = [
        'name',
    ];

    public function fillFromTmdb(array $data): static
    {
        // TODO: Implement fillFromTmdb() method.
    }
}
