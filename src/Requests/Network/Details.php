<?php

namespace Astrotomic\Tmdb\Requests\Network;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public function __construct(
        protected int $networkId,
    ) {
        parent::__construct();
    }

    public static function request(int $networkId): static
    {
        return new static($networkId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/network/%d', $this->networkId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'append_to_response' => implode(',', $this->append),
            ])
        )->throw();
    }
}
