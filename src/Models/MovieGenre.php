<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\ListMovieGenres;
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
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\MovieGenreBuilder query()
 * @method static \Astrotomic\Tmdb\Models\MovieGenre newModelInstance(array $attributes = [])
 * @method static \Astrotomic\Tmdb\Models\MovieGenre|\Illuminate\Database\Eloquent\Collection|null find(int $id, array $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection findMany(int[] $ids, array $columns = ['*'])
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

    public static function all($columns = ['*']): EloquentCollection
    {
        return DB::transaction(function () use ($columns): EloquentCollection {
            $data = rescue(fn () => ListMovieGenres::request()->send()->collect('genres'));

            if ($data instanceof Collection) {
                $data->each(fn (array $genre) => static::query()->updateOrCreate(
                    ['id' => $genre['id']],
                    ['name' => $genre['name']],
                ));
            }

            return parent::all($columns);
        });
    }

    public function updateFromTmdb(?string $locale = null): bool
    {
        $data = rescue(fn () => ListMovieGenres::request()->language($locale)->send()->collect('genres'));

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
