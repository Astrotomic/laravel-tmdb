<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\TvBuilder;
use Astrotomic\Tmdb\Eloquent\Relations\MorphManyCredits;
use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Enums\TvStatus;
use Astrotomic\Tmdb\Enums\TvType;
use Astrotomic\Tmdb\Enums\WatchProviderType;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\Tv\Details;
use Astrotomic\Tmdb\Requests\Tv\Recommendations;
use Astrotomic\Tmdb\Requests\Tv\Similars;
use Astrotomic\Tmdb\Requests\Tv\Popular;
use Astrotomic\Tmdb\Requests\Tv\TopRated;
use Astrotomic\Tmdb\Requests\Tv\WatchProviders;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\LazyCollection;

/**
 * @property int $id
 * @property bool $adult
 * @property bool $video
 * @property string|null $backdrop_path
 * @property string|null $poster_path
 * @property int|null $budget
 * @property int|null $revenue
 * @property string|null $homepage
 * @property string|null $imdb_id
 * @property string|null $original_language
 * @property string|null $original_title
 * @property float|null $popularity
 * @property \Carbon\Carbon|null $release_date
 * @property int|null $runtime
 * @property float|null $vote_average
 * @property int $vote_count
 * @property string[]|null $production_countries
 * @property string[]|null $spoken_languages
 * @property \Astrotomic\Tmdb\Enums\TvStatus|null $status
 * @property \Astrotomic\Tmdb\Enums\TvType|null $type
 * @property string|null $title
 * @property string|null $tagline
 * @property string|null $overview
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read array $translations
 * @property-read \Astrotomic\Tmdb\Models\Collection|null $collection
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\TvGenre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $credits
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $cast
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $crew
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\TvBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\TvBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\TvBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\TvBuilder
 */
class Tv extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'episode_run_time' => 'array',
        'languages' => 'array',
        'origin_country' => 'array',
        'vote_count' => 'int',
        'popularity' => 'float',
        'vote_average' => 'float',
        'first_air_date' => 'date',
        'last_air_date' => 'date',
        'production_countries' => 'array',
        'production_companies' => 'array',
        'seasons' => 'array',
        'spoken_languages' => 'array',
        'status' => TvStatus::class . ':nullable',
        'type' => TvType::class . ':nullable',
    ];

    public array $translatable = [
        'name',
        'tagline',
        'overview',
        'poster_path',
        'homepage',
    ];

    public static function popular(?int $limit): EloquentCollection
    {
        $ids = Popular::request()
            ->cursor()
            ->when($limit, fn (LazyCollection $collection) => $collection->take($limit))
            ->pluck('id');

        return static::query()->findMany($ids);
    }

    public static function toprated(?int $limit): EloquentCollection
    {
        $ids = TopRated::request()
            ->cursor()
            ->when($limit, fn (LazyCollection $collection) => $collection->take($limit))
            ->pluck('id');

        return static::query()->findMany($ids);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(TvGenre::class, 'tv_tv_genre');
    }

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'network_tv');
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
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
            'backdrop_path' => $data['backdrop_path'] ?: null,
            'episode_run_time' => $data['episode_run_time'] ?: null,
            'first_air_date' => $data['first_air_date'] ?: null,
            'homepage' => $data['homepage'] ?: null,
            'in_production' => $data['in_production'] ?: null,
            'languages' => $data['languages'] ?: null,
            'last_air_date' => $data['last_air_date'] ?: null,
            'name' => $data['name'] ?: null,
            'number_of_episodes' => $data['number_of_episodes'] ?: null,
            'number_of_seasons' => $data['number_of_seasons'] ?: null,
            'origin_country' => array_column($data['origin_country'] ?? [], 'iso_3166_1'),
            'original_language' => $data['original_language'] ?: null,
            'original_name' => $data['original_name'] ?: null,
            'overview' => $data['overview'] ?: null,
            'popularity' => $data['popularity'] ?: null,
            'poster_path' => $data['poster_path'] ?: null,
            'production_companies' => array_column($data['production_companies'] ?? [], 'name'),
            'production_countries' => array_column($data['production_countries'] ?: [], 'iso_3166_1'),
            'seasons' => $data['seasons'] ?: null,
            'spoken_languages' => array_column($data['spoken_languages'] ?: [], 'iso_639_1'),
            'status' => $data['status'] ?: null,
            'tagline' => $data['tagline'] ?: null,
            'type' => $data['type'] ?: null,
            'vote_average' => $data['vote_average'] ?: null,
            'vote_count' => $data['vote_count'] ?: 0,

        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('overview', $locale, trim($data['overview']) ?: null);
        $this->setTranslation('tagline', $locale, trim($data['tagline']) ?: null);
        $this->setTranslation('name', $locale, trim($data['name']) ?: null);
        $this->setTranslation('poster_path', $locale, trim($data['poster_path']) ?: null);

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $append = collect($with)
            ->map(fn (string $relation) => match ($relation) {
                'networks' => Details::APPEND_NETWORKS,
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
                ->map(static function (array $data) use ($locale): TvGenre {
                    $genre = TvGenre::query()->findOrNew($data['id']);
                    $genre->fillFromTmdb($data, $locale)->save();

                    return $genre;
                })
                ->pluck('id')
        );


        $this->networks()->sync(
            collect($data['networks'] ?: [])
                ->map(static function (array $data) use ($locale): Network {
                    $network = Network::query()->findOrNew($data['id']);
                    $network->fillFromTmdb($data, $locale)->save();

                    return $network;
                })
                ->pluck('id')
        );

        /*if ($data['belongs_to_collection']) {
            $this->collection()->associate(
                Collection::query()->findOrFail($data['belongs_to_collection']['id'])
            )->save();
        }*/

        /*if (isset($data['credits'])) {
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
        }*/

        return true;
    }

    public function newEloquentBuilder($query): TvBuilder
    {
        return new TvBuilder($query);
    }

    public function runtime(): ?CarbonInterval
    {
        if ($this->runtime === null) {
            return null;
        }

        return CarbonInterval::minutes($this->runtime)->cascade();
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

    public function recommendations(?int $limit): EloquentCollection
    {
        $ids = Recommendations::request($this->id)
            ->cursor()
            ->when($limit, fn (LazyCollection $collection) => $collection->take($limit))
            ->pluck('id');

        return static::query()->findMany($ids);
    }

    public function similars(?int $limit): EloquentCollection
    {
        $ids = Similars::request($this->id)
            ->cursor()
            ->when($limit, fn (LazyCollection $collection) => $collection->take($limit))
            ->pluck('id');

        return static::query()->findMany($ids);
    }

    public function watchProviders(?string $region = null, ?WatchProviderType $type = null): EloquentCollection
    {
        return WatchProvider::query()->findMany(
            WatchProviders::request($this->id)->send()->collect(sprintf(
                'results.%s.%s.*.provider_id',
                $region ?? '*',
                $type?->value ?? '*'
            ))
        );
    }
}
