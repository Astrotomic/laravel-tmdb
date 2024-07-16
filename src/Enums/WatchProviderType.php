<?php

namespace Astrotomic\Tmdb\Enums;

enum WatchProviderType: string
{
    case BUY = 'buy';
    case RENT = 'rent';
    case FLATRATE = 'flatrate';
}
