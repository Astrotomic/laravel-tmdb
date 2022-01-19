<?php

namespace Astrotomic\Tmdb\Requests\Tv;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public const APPEND_CREDITS = 'credits';

    public function __construct(
        protected int $tvId
    ) {
        parent::__construct();
    }

    public static function request(int $tvId): static
    {
        return new static($tvId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/tv/%d', $this->tvId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
