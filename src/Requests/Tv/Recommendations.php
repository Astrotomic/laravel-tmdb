<?php

namespace Astrotomic\Tmdb\Requests\Tv;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\LazyCollection;

class Recommendations extends Request
{
    protected int $page = 1;

    public function __construct(
        protected int $tvId,
    ) {
        parent::__construct();
    }

    public static function request(int $tvId): static
    {
        return new static($tvId);
    }

    public function page(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/tv/%d/recommendations', $this->tvId),
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'page' => $this->page,
            ])
        )->throw();
    }

    public function cursor(): LazyCollection
    {
        return LazyCollection::make(function (): Generator {
            $this->page = 1;
            do {
                $response = $this->send()->json();

                yield from $response['results'];

                $totalPages = $response['total_pages'];
                $this->page++;
            } while ($this->page <= $totalPages);
        });
    }
}
