<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'image')]
class Image implements Stringable
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Assert\File(maxSize: '1024k', mimeTypes: ['image/png'], mimeTypesMessage: 'validation.image')]
    private ?UploadedFile $file = null;

    #[ORM\Column(name: 'image_data', type: Types::TEXT, nullable: false)]
    private string $image;

    #[ORM\Column(name: 'title', type: Types::STRING, length: 50, nullable: false)]
    #[Assert\NotBlank]
    private string $title;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
        if ($this->file === null) {
            return;
        }

        $this->image = base64_encode($this->file->getContent());
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->title;
    }
}
