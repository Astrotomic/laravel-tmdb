<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\MovieGenre\ListAll;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string|null $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read array $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Movie[] $movies
 *
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder newModelQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder
 */
class MovieGenre extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'name',
    ];

    protected $casts = [
        'id' => 'int',
    ];

    public array $translatable = [
        'name',
    ];

    public static function all($columns = ['*']): EloquentCollection
    {
        return DB::transaction(function () use ($columns): EloquentCollection {
            $data = rescue(fn () => ListAll::request()->send()->collect('genres'));

            if ($data instanceof Collection) {
                $data->each(fn (array $genre) => static::query()->updateOrCreate(
                    ['id' => $genre['id']],
                    ['name' => $genre['name']],
                ));
            }

            return parent::all($columns);
        });
    }

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_movie_genre');
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $genre = $this->fill([
            'id' => $data['id'],
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('name', $locale, trim($data['name']) ?: null);

        return $genre;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $data = rescue(fn () => ListAll::request()->language($locale)->send()->collect('genres'));

        if ($data === null) {
            return false;
        }

        $data = $data->keyBy('id');

        if (! $data->has($this->id)) {
            return false;
        }

        return $this->fillFromTmdb($data->get($this->id), $locale)->save();
    }

    public function newEloquentBuilder($query): MovieGenreBuilder
    {
        return new MovieGenreBuilder($query);
    }
}
