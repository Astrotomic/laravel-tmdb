<?php

namespace Astrotomic\Tmdb\Requests;

use Illuminate\Http\Client\Response;

class GetMovieDetails extends Request
{
    public function __construct(
        protected int $movieId
    ) {
        parent::__construct();
    }

    public static function request(int $movieId): static
    {
        return new static($movieId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/movie/%d', $this->movieId),
            [
                'language' => $this->language ?? app()->getLocale(),
            ]
        )->throw();
    }
}
