<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder;
use Astrotomic\Tmdb\Eloquent\Relations\MorphManyCredits;
use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Enums\WatchProviderType;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\Movie\Details;
use Astrotomic\Tmdb\Requests\Movie\Recommendations;
use Astrotomic\Tmdb\Requests\Movie\Similars;
use Astrotomic\Tmdb\Requests\Movie\Popular;
use Astrotomic\Tmdb\Requests\Movie\TopRated;
use Astrotomic\Tmdb\Requests\Movie\Trending;
use Astrotomic\Tmdb\Requests\Movie\Upcoming;
use Astrotomic\Tmdb\Requests\Movie\WatchProviders;
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
 * @property \Astrotomic\Tmdb\Enums\MovieStatus|null $status
 * @property string|null $title
 * @property string|null $tagline
 * @property string|null $overview
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read array $translations
 * @property-read \Astrotomic\Tmdb\Models\Collection|null $collection
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\MovieGenre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $credits
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $cast
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $crew
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder
 */
class Movie extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'adult',
        'backdrop_path',
        'budget',
        'homepage',
        'imdb_id',
        'original_language',
        'original_title',
        'overview',
        'popularity',
        'poster_path',
        'release_date',
        'revenue',
        'video',
        'runtime',
        'vote_average',
        'vote_count',
        'production_countries',
        'spoken_languages',
        'tagline',
        'title',
        'status',
        'collection_id',
    ];

    protected $casts = [
        'id' => 'int',
        'adult' => 'bool',
        'video' => 'bool',
        'budget' => 'int',
        'revenue' => 'int',
        'runtime' => 'int',
        'vote_count' => 'int',
        'popularity' => 'float',
        'vote_average' => 'float',
        'release_date' => 'date',
        'production_countries' => 'array',
        'spoken_languages' => 'array',
        'status' => MovieStatus::class.':nullable',
        'collection_id' => 'int',
    ];

    public array $translatable = [
        'title',
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

    public static function upcoming(?int $limit): EloquentCollection
    {
        $ids = Upcoming::request()
            ->cursor()
            ->when($limit, fn (LazyCollection $collection) => $collection->take($limit))
            ->pluck('id');

        return static::query()->findMany($ids);
    }

    public static function trending(?int $limit, string $window = 'day'): EloquentCollection
    {
        $ids = Trending::request(window: $window)
            ->cursor()
            ->when($limit, fn (LazyCollection $collection) => $collection->take($limit))
            ->pluck('id');

        return static::query()->findMany($ids);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(MovieGenre::class, 'movie_movie_genre');
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
            'adult' => $data['adult'],
            'backdrop_path' => $data['backdrop_path'] ?: null,
            'budget' => $data['budget'] ?: null,
            'homepage' => $data['homepage'] ?: null,
            'imdb_id' => trim($data['imdb_id']) ?: null,
            'original_language' => $data['original_language'] ?: null,
            'original_title' => $data['original_title'] ?: null,
            'popularity' => $data['popularity'] ?: null,
            'release_date' => $data['release_date'] ?: null,
            'revenue' => $data['revenue'] ?: null,
            'video' => $data['video'],
            'runtime' => $data['runtime'] ?: null,
            'vote_average' => $data['vote_average'] ?: null,
            'vote_count' => $data['vote_count'] ?: 0,
            'production_countries' => array_column($data['production_countries'] ?: [], 'iso_3166_1'),
            'spoken_languages' => array_column($data['spoken_languages'] ?: [], 'iso_639_1'),
            'status' => $data['status'] ?: null,
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('overview', $locale, trim($data['overview']) ?: null);
        $this->setTranslation('tagline', $locale, trim($data['tagline']) ?: null);
        $this->setTranslation('title', $locale, trim($data['title']) ?: null);
        $this->setTranslation('poster_path', $locale, trim($data['poster_path']) ?: null);

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

        if (! $this->fillFromTmdb($data, $locale)->save()) {
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

    public function newEloquentBuilder($query): MovieBuilder
    {
        return new MovieBuilder($query);
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
