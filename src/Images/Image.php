<?php

namespace Astrotomic\Tmdb\Images;

use Illuminate\Contracts\Support\Htmlable;
use Stringable;

abstract class Image implements Htmlable, Stringable
{
    public const SIZE_ORIGINAL = null;

    protected ?int $size;

    public function __construct(
        protected ?string $path,
        protected ?string $alt = null,
    ) {}

    public function size(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    abstract public function width(): int;

    abstract public function height(): int;

    public function url(): ?string
    {
        if (empty($this->path)) {
            return null;
        }

        return sprintf(
            '%s/%s/%s',
            'https://image.tmdb.org/t/p',
            $this->size
                ? "w{$this->size}"
                : 'original',
            ltrim($this->path, '/')
        );
    }

    public function fallback(): string
    {
        return sprintf(
            '%s/%dx%d/9ca3af/ffffff.jpg?text=%s',
            'https://via.placeholder.com',
            $this->width(),
            $this->height(),
            urlencode($this->alt)
        );
    }

    public function __toString(): string
    {
        if (empty($this->path)) {
            return $this->fallback();
        }

        return $this->url();
    }

    public function toHtml(): string
    {
        return <<<HTML
        <img src="{$this}" alt="{$this->alt}" loading="lazy" width="{$this->width()}" height="{$this->height()}"/>
        HTML;
    }
}
