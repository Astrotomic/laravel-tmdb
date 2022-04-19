<?php

namespace Astrotomic\Tmdb\Requests\Tv;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\LazyCollection;

class Popular extends Request
{
    protected int $page = 1;
    protected ?string $region = null;

    public static function request(): static
    {
        return new static();
    }

    public function page(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function region(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function send(): Response
    {
        return $this->request->get(
            '/tv/popular',
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'region' => $this->region ?? Tmdb::region(),
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
