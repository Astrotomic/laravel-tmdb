<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\CollectionBuilder;
use Astrotomic\Tmdb\Eloquent\Relations\HasManyMovies;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\Collection\Details;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $overview
 * @property string|null $backdrop_path
 * @property string|null $poster_path
 * @property-read array $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Movie[] $movies
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\CollectionBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\CollectionBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\CollectionBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\CollectionBuilder
 */
class Collection extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'name',
        'overview',
        'poster_path',
        'backdrop_path',
    ];

    protected $casts = [
        'id' => 'int',
    ];

    public array $translatable = [
        'name',
        'overview',
        'poster_path',
    ];

    public function movies(): HasManyMovies
    {
        /** @var \Astrotomic\Tmdb\Models\Movie $instance */
        $instance = $this->newRelatedInstance(Movie::class);

        return new HasManyMovies(
            $instance->newQuery(),
            $this,
            $instance->qualifyColumn($this->getForeignKey()),
            $this->getKeyName()
        );
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $this->fill([
            'id' => $data['id'],
            'backdrop_path' => $data['backdrop_path'] ?: null,
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('overview', $locale, trim($data['overview']) ?: null);
        $this->setTranslation('name', $locale, trim($data['name']) ?: null);
        $this->setTranslation('poster_path', $locale, trim($data['poster_path']) ?: null);

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $data = rescue(
            fn () =>Details::request($this->id)
                ->language($locale)
                ->send()
                ->json()
        );

        if ($data === null) {
            return false;
        }

        return $this->fillFromTmdb($data, $locale)->save();
    }

    public function newEloquentBuilder($query): CollectionBuilder
    {
        return new CollectionBuilder($query);
    }

    public function poster(): Poster
    {
        return new Poster(
            $this->poster_path,
            $this->title
        );
    }

    public function backdrop(): Backdrop
    {
        return new Backdrop(
            $this->backdrop_path,
            $this->title
        );
    }
}
