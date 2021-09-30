<?php

namespace Astrotomic\Tmdb\Requests;

use Astrotomic\Tmdb\Facades\Tmdb;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\LazyCollection;

class ListMovieSimilars extends Request
{
    protected int $page = 1;

    public function __construct(
        protected int $movieId,
    ) {
        parent::__construct();
    }

    public static function request(int $movieId): static
    {
        return new static($movieId);
    }

    public function page(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function send(): Response
    {
        return $this->request->get(
            sprintf('/movie/%d/similar', $this->movieId),
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
