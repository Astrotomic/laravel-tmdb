<?php

namespace Astrotomic\Tmdb\Requests\Person;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public const APPEND_MOVIE_CREDITS = 'movie_credits';

    public function __construct(
        protected int $personId
    ) {
        parent::__construct();
    }

    public static function request(int $personId): static
    {
        return new static($personId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/person/%d', $this->personId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
