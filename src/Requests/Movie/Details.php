<?php

namespace Astrotomic\Tmdb\Requests\Movie;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public const APPEND_CREDITS = 'credits';

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
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
