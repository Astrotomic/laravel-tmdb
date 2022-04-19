<?php

namespace Astrotomic\Tmdb\Requests\Person;

class Trending extends \Astrotomic\Tmdb\Requests\Trending\Trending
{
    public function __construct(string $window = 'day')
    {
        parent::__construct('person', $window);
    }

    public static function request(string $window = 'day'): static
    {
        return new static($window);
    }
}
