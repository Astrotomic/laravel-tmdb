<?php

namespace Astrotomic\Tmdb\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self UNKNOWN()
 * @method static self FEMALE()
 * @method static self MALE()
 * @method static self NON_BINARY()
 */
class Gender extends Enum
{
    protected static function values(): array
    {
        return [
            'UNKNOWN' => 0,
            'FEMALE' => 1,
            'MALE' => 2,
            'NON_BINARY' => 3,
        ];
    }
}
