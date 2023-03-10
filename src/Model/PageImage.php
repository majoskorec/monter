<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Image;

final class PageImage
{
    public function __construct(
        public readonly string $typ,
        public readonly ?Image $image,
    ) {
    }

    public function data(): ?string
    {
        return $this->image?->getImage();
    }

    public function title(): ?string
    {
        return $this->image?->getTitle();
    }

    public function isSet(): bool
    {
        return $this->image !== null;
    }

    public function id(): ?int
    {
        return $this->image?->getId();
    }
}
