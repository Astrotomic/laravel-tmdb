<?php

namespace Astrotomic\Tmdb\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self RUMORED()
 * @method static self PLANNED()
 * @method static self IN_PRODUCTION()
 * @method static self POST_PRODUCTION()
 * @method static self RELEASED()
 * @method static self CANCELED()
 */
class MovieStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'RUMORED' => 'Rumored',
            'PLANNED' => 'Planned',
            'IN_PRODUCTION' => 'In Production',
            'POST_PRODUCTION' => 'Post Production',
            'RELEASED' => 'Released',
            'CANCELED' => 'Canceled',
        ];
    }
}
