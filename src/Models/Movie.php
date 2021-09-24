<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder;
use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;
use Astrotomic\Tmdb\Requests\GetMovieDetails;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

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
 * @property \Astrotomic\Tmdb\Enums\MovieStatus|null $status
 * @property string|null $title
 * @property string|null $tagline
 * @property string|null $overview
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read array $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\MovieGenre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $credits
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $cast
 * @property-read \Illuminate\Database\Eloquent\Collection|\Astrotomic\Tmdb\Models\Credit[] $crew
 *
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\MovieBuilder query()
 * @method static \Astrotomic\Tmdb\Models\Movie newModelInstance(array $attributes = [])
 * @method static \Astrotomic\Tmdb\Models\Movie|\Illuminate\Database\Eloquent\Collection|null find(int|int[]|\Illuminate\Contracts\Support\Arrayable $id, array $columns = ['*'])
 * @method static \Astrotomic\Tmdb\Models\Movie|\Illuminate\Database\Eloquent\Collection findOrFail(int|int[]|\Illuminate\Contracts\Support\Arrayable $id, array $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection findMany(int[]|\Illuminate\Contracts\Support\Arrayable $ids, array $columns = ['*'])
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
    ];

    public array $translatable = [
        'title',
        'tagline',
        'overview',
        'poster_path',
        'homepage',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(MovieGenre::class, 'movie_movie_genre');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder
     */
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'media');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder
     */
    public function cast(): MorphMany
    {
        return $this->credits()->whereCreditType(CreditType::CAST());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder
     */
    public function crew(): MorphMany
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
                'cast', 'crew', 'credits' => GetMovieDetails::APPEND_CREDITS,
                default => null,
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $data = rescue(
            fn () => GetMovieDetails::request($this->id)
                ->language($locale)
                ->append(...$append)
                ->send()
                ->json()
        );

        if ($data === null) {
            return false;
        }

        return DB::transaction(function () use ($data, $locale, $with): bool {
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

            if (isset($data['credits'])) {
                if (in_array('credits', $with) || in_array('cast', $with)) {
                    foreach ($data['credits']['cast'] as $cast) {
                        Credit::query()->find($cast['credit_id']);
                    }
                }

                if (in_array('credits', $with) || in_array('crew', $with)) {
                    foreach ($data['credits']['crew'] as $crew) {
                        Credit::query()->find($crew['credit_id']);
                    }
                }
            }

            return true;
        });
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
}
