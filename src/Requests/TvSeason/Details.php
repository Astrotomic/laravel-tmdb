<?php

namespace Astrotomic\Tmdb\Requests\TvSeason;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public function __construct(
        protected int $tvId,
        protected int $tvSeasonId,
    ) {
        parent::__construct();
    }

    public static function request(int $tvId, int $tvSeasonId): static
    {
        return new static($tvId, $tvSeasonId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/tv/%d/season/%d', $this->tvId, $this->tvSeasonId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
