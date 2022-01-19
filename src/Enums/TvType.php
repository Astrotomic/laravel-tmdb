<?php

namespace Astrotomic\Tmdb\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SCRIPTED()
 * @method static self REALITY()
 * @method static self MINISERIES()
 */
class TvType extends Enum
{
    protected static function values(): array
    {
        return [
            'SCRIPTED' => 'Scripted',
            'REALITY' => 'Reality',
            'MINISERIES' => 'Miniseries',
            // TODO: Add more values, need testing, no official documentation available
        ];
    }
}
