<?php

namespace Astrotomic\Tmdb\Images;

class Backdrop extends Image
{
    public const SIZE_W300 = 300;

    public const SIZE_W780 = 780;

    public const SIZE_W1280 = 1280;

    protected ?int $size = self::SIZE_W1280;

    public function width(): int
    {
        return $this->size ?? 1920;
    }

    public function height(): int
    {
        return $this->size
            ? $this->size / 16 * 9
            : 1080;
    }
}
