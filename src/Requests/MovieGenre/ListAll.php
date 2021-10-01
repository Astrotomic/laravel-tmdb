<?php

namespace Astrotomic\Tmdb\Requests\MovieGenre;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class ListAll extends Request
{
    public static function request(): static
    {
        return new static();
    }

    public function send(): Response
    {
        return $this->request->get(
            '/genre/movie/list',
            [
                'language' => $this->language ?? Tmdb::language(),
            ]
        )->throw();
    }
}
