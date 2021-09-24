<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\PersonBuilder;
use Astrotomic\Tmdb\Enums\Gender;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\GetPersonDetails;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\PersonBuilder query()
 */
class Person extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'name',
        'adult',
        'also_known_as',
        'biography',
        'birthday',
        'deathday',
        'gender',
        'homepage',
        'imdb_id',
        'known_for_department',
        'place_of_birth',
        'popularity',
        'profile_path',
    ];

    protected $casts = [
        'id' => 'int',
        'adult' => 'bool',
        'also_known_as' => 'array',
        'birthday' => 'date',
        'deathday' => 'date',
        'gender' => Gender::class,
        'popularity' => 'float',
    ];

    public array $translatable = [
        'biography',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder
     */
    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder
     */
    public function movie_credits(): HasMany
    {
        return $this->credits()->whereMediaType(Movie::class);
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $this->fill([
            'id' => $data['id'],
            'adult' => $data['adult'],
            'name' => $data['name'] ?: null,
            'also_known_as' => $data['also_known_as'] ?: [],
            'homepage' => $data['homepage'] ?: null,
            'imdb_id' => trim($data['imdb_id']) ?: null,
            'birthday' => $data['birthday'] ?: null,
            'gender' => $data['gender'] ?: 0,
            'known_for_department' => $data['known_for_department'] ?: null,
            'place_of_birth' => $data['place_of_birth'] ?: null,
            'profile_path' => $data['profile_path'] ?: null,
            'popularity' => $data['popularity'] ?: null,
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('biography', $locale, trim($data['biography']) ?: null);

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $append = collect($with)
            ->map(fn (string $relation) => match ($relation) {
                'movie_credits' => GetPersonDetails::APPEND_MOVIE_CREDITS,
                default => null,
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $data = GetPersonDetails::request($this->id)
                ->language($locale)
                ->append(...$append)
                ->send()
                ->json();

        if ($data === null) {
            return false;
        }

        return DB::transaction(function () use ($data, $locale): bool {
            if (! $this->fillFromTmdb($data, $locale)->save()) {
                return false;
            }

            if (isset($data['movie_credits'])) {
                foreach ($data['movie_credits']['cast'] as $cast) {
                    Credit::query()->find($cast['credit_id']);
                }
                foreach ($data['movie_credits']['crew'] as $crew) {
                    Credit::query()->find($crew['credit_id']);
                }
            }

            return true;
        });
    }

    public function newEloquentBuilder($query): PersonBuilder
    {
        return new PersonBuilder($query);
    }
}
