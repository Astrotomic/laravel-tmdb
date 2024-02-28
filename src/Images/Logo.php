<?php

namespace Astrotomic\Tmdb\Images;

class Logo extends Image
{
    public const SIZE_W45 = 45;

    public const SIZE_W92 = 92;

    public const SIZE_W154 = 154;

    public const SIZE_W185 = 185;

    public const SIZE_W300 = 300;

    public const SIZE_W500 = 500;

    protected ?int $size = self::SIZE_W92;

    public function width(): int
    {
        return $this->size ?? self::SIZE_W92;
    }

    public function height(): int
    {
        return $this->width();
    }
}
