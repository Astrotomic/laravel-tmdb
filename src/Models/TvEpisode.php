<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\TvEpisodeBuilder;
use Astrotomic\Tmdb\Eloquent\Relations\MorphManyCredits;
use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Enums\WatchProviderType;
use Astrotomic\Tmdb\Images\Still;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\TvEpisode\Details;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\LazyCollection;

/**
 * @property int $id
 * @property \Carbon\Carbon|null $air_date
 * @property int|null $episode_number
 * @property string|null $name
 * @property string|null $overview
 * @property string|null $production_code
 * @property int|null $season_number
 * @property string|null $still_path
 * @property float|null $vote_average
 * @property int $vote_count
 * 
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read array $translations
 * @property-read \Astrotomic\Tmdb\Models\Collection|null $collection
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\TvSeason[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $credits
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $cast
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $crew
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\TvEpisodeBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\TvEpisodeBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\TvEpisodeBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\TvEpisodeBuilder
 */
class TvEpisode extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'air_date',
        'episode_number',
        'name',
        'overview',
        'production_code',
        'season_number',
        'still_path',
        'vote_average',
        'vote_count',
        'tv_season_id',
    ];

    protected $casts = [
        'id' => 'int',
        'air_date' => 'date',
        'episode_number' => 'int',
        'name' => 'string',
        'overview' => 'string',
        'production_code' => 'string',
        'season_number' => 'int',
        'still_path' => 'string',
        'vote_average' => 'float',
        'vote_count' => 'int',
        'tv_season_id' => 'int',
    ];

    public array $translatable = [
        'name',
        'overview',
        'still_path',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(TvSeason::class, 'tv_season_id');
    }

    public function credits(): MorphManyCredits
    {
        /** @var \Astrotomic\Tmdb\Models\Credit $instance */
        $instance = $this->newRelatedInstance(Credit::class);

        return new MorphManyCredits(
            $instance->newQuery(),
            $this,
            $instance->qualifyColumn('media_type'),
            $instance->qualifyColumn('media_id'),
            $this->getKeyName()
        );
    }

    public function guest_stars(): MorphManyCredits
    {
        return $this->guest_stars()->whereCreditType(CreditType::GUEST_STARS());
    }

    public function cast(): MorphManyCredits
    {
        return $this->credits()->whereCreditType(CreditType::CAST());
    }

    public function crew(): MorphManyCredits
    {
        return $this->credits()->whereCreditType(CreditType::CREW());
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $this->fill([
            'id' => $data['id'],
            'air_date' => $data['air_date'] ?: null,
            'episode_number' => $data['episode_number'] ?: null,
            'name' => $data['name'] ?: null,
            'overview' => $data['overview'] ?: null,
            'production_code' => $data['production_code'] ?: null,
            'season_number' => $data['season_number'] ?: null,
            'still_path' => $data['still_path'] ?: null,
            'vote_average' => $data['vote_average'] ?: null,
            'vote_count' => $data['vote_count'] ?: null,
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('overview', $locale, trim($data['overview']) ?: null);
        $this->setTranslation('name', $locale, trim($data['title']) ?: null);
        $this->setTranslation('still_path', $locale, trim($data['poster_path']) ?: null);

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $append = collect($with)
            ->map(fn (string $relation) => match ($relation) {
                'cast', 'crew', 'credits' => Details::APPEND_CREDITS,
                default => null,
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $data = rescue(
            fn () => Details::request($this->id)
                ->language($locale)
                ->append(...$append)
                ->send()
                ->json()
        );

        if ($data === null) {
            return false;
        }

        if (!$this->fillFromTmdb($data, $locale)->save()) {
            return false;
        }

        $this->genres()->sync(
            collect($data['genres'] ?: [])
                ->map(static function (array $data) use ($locale): MovieGenre {
                    $genre = MovieGenre::query()->findOrNew($data['id']);
                    $genre->fillFromTmdb($data, $locale)->save();

                    return $genre;
                })
                ->pluck('id')
        );

        if ($data['belongs_to_collection']) {
            $this->collection()->associate(
                Collection::query()->findOrFail($data['belongs_to_collection']['id'])
            )->save();
        }

        if (isset($data['credits'])) {
            if (in_array('credits', $with) || in_array('cast', $with)) {
                foreach ($data['credits']['cast'] as $cast) {
                    Credit::query()->findOrFail($cast['credit_id']);
                }
            }

            if (in_array('credits', $with) || in_array('crew', $with)) {
                foreach ($data['credits']['crew'] as $crew) {
                    Credit::query()->findOrFail($crew['credit_id']);
                }
            }
        }

        return true;
    }

    public function newEloquentBuilder($query): TvEpisodeBuilder
    {
        return new TvEpisodeBuilder($query);
    }

    public function runtime(): ?CarbonInterval
    {
        if ($this->runtime === null) {
            return null;
        }

        return CarbonInterval::minutes($this->runtime)->cascade();
    }
}
