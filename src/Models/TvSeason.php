<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\TvSeasonBuilder;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\TvSeason\Details;

/**
 * @property int $id
 * @property \Carbon\Carbon|null $air_date
 * @property string|null $name
 * @property string|null $overview
 * @property string|null $poster_path
 * @property int|null $season_number
 * @property-read array $translations
 * @property-read \Astrotomic\Tmdb\Models\Collection|null $collection
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\TvSeasonBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\TvSeasonBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\TvSeasonBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\TvSeasonBuilder
 */
class TvSeason extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'air_date',
        'name',
        'overview',
        'poster_path',
        'season_number',
    ];

    protected $casts = [
        'id' => 'int',
        'air_date' => 'string',
        'name' => 'string',
        'overview' => 'string',
        'poster_path' => 'string',
        'season_number' => 'int',
    ];

    public array $translatable = [
        'name',
        'overview',
        'poster_path',
    ];

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $this->fill([
            'id' => $data['id'],
            'air_date' => $data['air_date'] ?: null,
            'name' => $data['name'] ?: null,
            'overview' => $data['overview'] ?: null,
            'poster_path' => $data['poster_path'] ?: null,
            'season_number' => $data['season_number'] ?: null,
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('overview', $locale, trim($data['overview']) ?: null);
        $this->setTranslation('name', $locale, trim($data['name']) ?: null);
        $this->setTranslation('poster_path', $locale, trim($data['poster_path']) ?: null);

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        /*$append = collect($with)
            ->map(fn (string $relation) => match ($relation) {
                'networks' => Details::APPEND_NETWORKS,
                default => null,
            })
            ->filter()
            ->unique()
            ->values()
            ->all();*/

        $data = rescue(
            fn () => Details::request($this->tv_id, $this->id)
                ->language($locale)
                //->append(...$append)
                ->send()
                ->json()
        );

        if ($data === null) {
            return false;
        }

        if (! $this->fillFromTmdb($data, $locale)->save()) {
            return false;
        }

        if ($data['belongs_to_tv']) {
            $this->tv()->associate(
                Tv::query()->findOrFail($data['belongs_to_tv']['id'])
            )->save();
        }

        return true;
    }

    public function newEloquentBuilder($query): TvSeasonBuilder
    {
        return new TvSeasonBuilder($query);
    }

    public function poster(): Poster
    {
        return new Poster(
            $this->poster_path,
            $this->title
        );
    }
}
