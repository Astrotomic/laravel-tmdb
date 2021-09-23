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
 * @method static \Astrotomic\Tmdb\Eloquent\Builders\CreditBuilder query()
 */
class Credit extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
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
        $data = GetCreditDetails::request($this->id)
                ->send()
                ->json();

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
