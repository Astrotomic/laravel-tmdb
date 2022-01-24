<?php

namespace Astrotomic\Tmdb\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CAST()
 * @method static self CREW()
 */
class CreditType extends Enum
{
    protected static function values(): array
    {
        return [
            'CAST' => 'cast',
            'CREW' => 'crew',
            'GUEST_STARS' => 'guest_stars',
        ];
    }
}
