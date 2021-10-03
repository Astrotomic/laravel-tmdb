<?php

namespace Astrotomic\Tmdb\Requests\Movie;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Credits extends Request
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
            sprintf('/movie/%d/credits', $this->movieId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
            ])
        )->throw();
    }
}
