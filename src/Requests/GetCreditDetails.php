<?php

namespace Astrotomic\Tmdb\Requests;

use Illuminate\Http\Client\Response;

class GetCreditDetails extends Request
{
    public function __construct(
        protected string $creditId
    ) {
        parent::__construct();
    }

    public static function request(string $creditId): static
    {
        return new static($creditId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/credit/%s', $this->creditId)
        )->throw();
    }
}
