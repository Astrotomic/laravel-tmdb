<?php

namespace Astrotomic\Tmdb\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self BUY()
 * @method static self RENT()
 * @method static self FLATRATE()
 */
class WatchProviderType extends Enum
{
    protected static function values(): array
    {
        return [
            'BUY' => 'buy',
            'RENT' => 'rent',
            'FLATRATE' => 'flatrate',
        ];
    }
}
