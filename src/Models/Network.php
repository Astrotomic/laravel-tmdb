<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\NetworkBuilder;
use Astrotomic\Tmdb\Models\Concerns\HasTranslations;

use Astrotomic\Tmdb\Images\Logo;
use Astrotomic\Tmdb\Requests\Network\Details;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string|null $headquarters
 * @property string|null $homepage
 * @property string|null $logo_path
 * @property string|null $name
 * @property string[]|null $origin_country
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read array $translations
 * @property-read \Astrotomic\Tmdb\Models\Collection|null $collection
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\NetworkBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\NetworkBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\NetworkBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\NetworkBuilder
 */
class Network extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'headquarters',
        'homepage',
        'logo_path',
        'name',
        'origin_country',
    ];

    protected $casts = [
        'id' => 'int',
        'headquarters' => 'string',
        'homepage' => 'string',
        'logo_path' => 'string',
        'name' => 'string',
        'origin_country' => 'array',
    ];

    public array $translatable = [
        'name',
        'homepage',
        'logo_path',
    ];

    public function tvs(): BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'tv_network');
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $this->fill([
            'id' => $data['id'],
            'headquarters' => $data['headquarters'] ?? null,
            'homepage' => $data['homepage'] ?? null,
            'logo_path' => $data['logo_path'] ?? null,
            'name' => $data['name'] ?? null,
            'origin_country' => $data['origin_country'] ?? [], 'iso_3166_1',
        ]);

        $locale ??= $this->getLocale();

        $this->setTranslation('homepage', $locale, trim($data['homepage']) ?: null);
        $this->setTranslation('name', $locale, trim($data['name']) ?: null);
        $this->setTranslation('logo_path', $locale, trim($data['logo_path']) ?: null);

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        /*$append = collect($with)
            ->map(fn (string $relation) => match ($relation) {
                'cast', 'crew', 'credits' => Details::APPEND_CREDITS,
                default => null,
            })
            ->filter()
            ->unique()
            ->values()
            ->all();*/

        $data = rescue(
            fn () => Details::request($this->id)
                ->language($locale)
                //->append(...$append)
                ->send()
                ->json()
        );

        if ($data === null) {
            return false;
        }

        if (!$this->fillFromTmdb($data, $locale)->save()) {
            return false;
        }

        return true;
    }

    public function newEloquentBuilder($query): NetworkBuilder
    {
        return new NetworkBuilder($query);
    }


    public function logo(): Logo
    {
        return new Logo(
            $this->logo_path,
            $this->name
        );
    }
}
