<?php

namespace Astrotomic\Tmdb\Requests;

use Illuminate\Http\Client\Response;

class ListMovieGenres extends Request
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
                'language' => $this->language ?? app()->getLocale(),
            ]
        )->throw();
    }
}
