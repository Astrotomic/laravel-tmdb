<?php

namespace Astrotomic\Tmdb\Requests\Movie;

use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class WatchProviders extends Request
{
    public function __construct(
        protected int $movieId,
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
            sprintf('/movie/%d/watch/providers', $this->movieId),
        )->throw();
    }
}
