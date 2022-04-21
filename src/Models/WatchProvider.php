<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\WatchProviderBuilder;
use Astrotomic\Tmdb\Eloquent\Scopes\OrderByDisplayPriority;
use Astrotomic\Tmdb\Images\Logo;
use Astrotomic\Tmdb\Requests\WatchProvider\MovieListAll;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $logo_path
 * @property int $display_priority
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method \Astrotomic\Tmdb\Eloquent\Builders\WatchProviderBuilder newModelQuery()
 * @method \Astrotomic\Tmdb\Eloquent\Builders\WatchProviderBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\WatchProviderBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\WatchProviderBuilder
 */
class WatchProvider extends Model
{
    protected $fillable = [
        'id',
        'name',
        'logo_path',
        'display_priority',
    ];

    protected $casts = [
        'id' => 'int',
        'display_priority' => 'int',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByDisplayPriority());
    }

    public static function all($columns = ['*']): EloquentCollection
    {
        $data = rescue(fn () => MovieListAll::request()->send()->collect('results'));

        if ($data instanceof Collection) {
            $data->each(fn (array $provider) => static::query()->updateOrCreate(
                ['id' => $provider['provider_id']],
                [
                    'display_priority' => $provider['display_priority'],
                    'name' => $provider['provider_name'],
                    'logo_path' => $provider['logo_path'],
                ],
            ));
        }

        return parent::all($columns);
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        return $this->fill([
            'id' => $data['provider_id'],
            'display_priority' => $data['display_priority'],
            'name' => $data['provider_name'],
            'logo_path' => $data['logo_path'],
        ]);
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $data = MovieListAll::request()->send()->collect('results');

        if ($data === null) {
            return false;
        }

        $data = $data->keyBy('provider_id');

        if (! $data->has($this->id)) {
            return false;
        }

        return $this->fillFromTmdb($data->get($this->id), $locale)->save();
    }

    public function logo(): Logo
    {
        return new Logo(
            $this->logo_path,
            $this->name
        );
    }

    public function newEloquentBuilder($query): WatchProviderBuilder
    {
        return new WatchProviderBuilder($query);
    }
}
