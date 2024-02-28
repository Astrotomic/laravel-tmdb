<?php

namespace Astrotomic\Tmdb\Enums;

enum MovieStatus: string
{
    case RUMORED = 'Rumored';
    case PLANNED = 'Planned';
    case IN_PRODUCTION = 'In Production';
    case POST_PRODUCTION = 'Post Production';
    case RELEASED = 'Released';
    case CANCELED = 'Canceled';
}
