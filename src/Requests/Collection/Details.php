<?php

namespace Astrotomic\Tmdb\Requests\Collection;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;

class Details extends Request
{
    public function __construct(
        protected int $collectionId
    ) {
        parent::__construct();
    }

    public static function request(int $collectionId): static
    {
        return new static($collectionId);
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/collection/%d', $this->collectionId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
            ])
        )->throw();
    }
}
