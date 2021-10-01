<?php

namespace Astrotomic\Tmdb\Requests\WatchProvider;

use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class MovieListAll extends Request
{
    public static function request(): static
    {
        return new static();
    }

    public function send(): Response
    {
        return $this->request->get('/watch/providers/movie')->throw();
    }
}
