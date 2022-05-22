<?php

namespace Astrotomic\Tmdb\Client\DTO;

use Astrotomic\Tmdb\Images\Logo;

class WatchProvider
{
    public function __construct(
        public int $id,
        public string $name,
        public int $displayPriority,
        public string $logoPath,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new static(
            id: $data['provider_id'],
            name: $data['provider_name'],
            displayPriority: $data['display_priority'],
            logoPath: $data['logo_path'],
        );
    }

    public function logo(): Logo
    {
        return new Logo(
            $this->logoPath,
            $this->name
        );
    }
}
