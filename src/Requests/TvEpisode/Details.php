<?php

namespace Astrotomic\Tmdb\Requests\TvEpisode;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public const APPEND_CREDITS = 'credits';

    public function __construct(
        protected int $tvId,
        protected int $tvSeasonNumber,
        protected int $tvEpisodeNumber
    ) {
        parent::__construct();
    }

    public static function request(int $tvId, int $tvSeasonNumber, int $tvEpisodeNumber): static
    {
        return new static($tvId, $tvSeasonNumber, $tvEpisodeNumber);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/tv/%d/season/%d/episode/%d', [$this->tvId, $this->tvSeasonNumber, $this->tvEpisodeNumber]),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
