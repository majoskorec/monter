<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Image;
use JetBrains\PhpStorm\Pure;

final class PageImage
{
    public function __construct(private string $typ, private ?Image $image)
    {
    }

    public function getTyp(): string
    {
        return $this->typ;
    }

    #[Pure]
    public function getData(): ?string
    {
        return $this->image?->getImage();
    }

    #[Pure]
    public function getTitle(): ?string
    {
        return $this->image?->getTitle();
    }

    public function isSet(): bool
    {
        return $this->image !== null;
    }

    #[Pure]
    public function getId(): ?int
    {
        return $this->image?->getId();
    }
}
