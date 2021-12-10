<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: "image")]
class Image
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Assert\File(maxSize: "1024k", mimeTypes: ["image/png"], mimeTypesMessage: "validation.image")]
    private ?UploadedFile $file = null;

    #[ORM\Column(name: "image_data", type: "text", nullable: false)]
    private string $image;

    #[ORM\Column(name: "title", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank]
    private string $title;

    public function __toString(): string
    {
        return $this->title;
    }

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
}
