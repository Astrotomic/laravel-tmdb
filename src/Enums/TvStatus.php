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
 * @method static self RETURNING_SERIES()
 * @method static self ENDED()
 * @method static self PILOT()
 */
class TvStatus extends Enum
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
            'RETURNING_SERIES' => 'Returning Series',
            'ENDED' => 'Ended',
            'PILOT' => 'Pilot',
            // TODO: Implement more values, need testing, no official documentation available
        ];
    }
}
