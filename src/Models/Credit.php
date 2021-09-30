<?php

namespace Astrotomic\Tmdb\Models;

use Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder;
use Astrotomic\Tmdb\Enums\CreditType;
use Astrotomic\Tmdb\Requests\GetCreditDetails;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @property string $id
 * @property int $person_id
 * @property string $media_type
 * @property int $media_id
 * @property \Astrotomic\Tmdb\Enums\CreditType $credit_type
 * @property string|null $department
 * @property string|null $job
 * @property string|null $character
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Astrotomic\Tmdb\Models\Movie|\Astrotomic\Tmdb\Models\Model $media
 * @property-read \Astrotomic\Tmdb\Models\Person $person
 *
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder newModelQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder newQuery()
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder query()
 *
 * @mixin \Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder
 */
class Credit extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'person_id',
        'media_id',
        'media_type',
        'credit_type',
        'department',
        'job',
        'character',
    ];

    protected $casts = [
        'id' => 'string',
        'person_id' => 'int',
        'media_id' => 'int',
        'credit_type' => CreditType::class,
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function media(): MorphTo
    {
        return $this->morphTo('media');
    }

    public function fillFromTmdb(array $data, ?string $locale = null): static
    {
        $this->fill([
            'credit_type' => $data['credit_type'],
            'department' => $data['department'] ?: null,
            'job' => $data['job'] ?: null,
            'character' => Arr::get($data, 'media.character') ?: null,
        ]);

        $this->person()->associate(Person::query()->find($data['person']['id']));
        if ($data['media_type'] === 'movie') {
            $this->media()->associate(Movie::query()->find($data['media']['id']));
        }

        return $this;
    }

    public function updateFromTmdb(?string $locale = null, array $with = []): bool
    {
        $data = rescue(
            fn () => GetCreditDetails::request($this->id)
                ->send()
                ->json()
        );

        if ($data === null) {
            return false;
        }

        return DB::transaction(function () use ($data, $locale): bool {
            return $this->fillFromTmdb($data, $locale)->save();
        });
    }

    public function newEloquentBuilder($query): CreditBuilder
    {
        return new CreditBuilder($query);
    }
}
