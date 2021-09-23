<?php

namespace Astrotomic\Tmdb\Requests;

use Illuminate\Http\Client\Response;

class GetMovieDetails extends Request
{
    public const APPEND_CREDITS = 'credits';

    public function __construct(
        protected int $creditId
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
            sprintf('/movie/%d', $this->creditId),
            array_filter([
                'language' => $this->language ?? app()->getLocale(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
