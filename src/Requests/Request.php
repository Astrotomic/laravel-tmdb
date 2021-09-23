<?php

namespace Astrotomic\Tmdb\Requests;

use BadMethodCallException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * @mixin \Illuminate\Http\Client\PendingRequest
 */
abstract class Request
{
    protected PendingRequest $request;
    protected ?string $language = null;
    protected array $append = [];

    public function __construct()
    {
        $this->request = Http::baseUrl('https://api.themoviedb.org/3')
            ->acceptJson()
            ->withToken(config('services.tmdb.token'));
    }

    public function language(?string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function append(string ...$append): static
    {
        $this->append = $append;

        return $this;
    }

    abstract public function send(): Response;

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->request, $name)) {
            return call_user_func_array([$this->request, $name], $arguments);
        }

        throw new BadMethodCallException();
    }
}
