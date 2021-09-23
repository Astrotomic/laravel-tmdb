<?php

namespace Astrotomic\Tmdb\Requests;

use Illuminate\Http\Client\Response;

class GetPersonDetails extends Request
{
    public const APPEND_MOVIE_CREDITS = 'movie_credits';

    public function __construct(
        protected int $creditId
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
            sprintf('/person/%d', $this->creditId),
            array_filter([
                'language' => $this->language ?? app()->getLocale(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
