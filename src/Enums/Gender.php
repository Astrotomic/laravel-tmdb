<?php

namespace Astrotomic\Tmdb\Enums;

enum Gender: int
{
    case UNKNOWN = 0;
    case FEMALE = 1;
    case MALE = 2;
    case NON_BINARY = 3;
}
