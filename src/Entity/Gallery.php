<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: GalleryRepository::class)]
#[ORM\Table(name: "gallery")]
#[UniqueEntity(fields: ["urlKey", "page"])]
class Gallery
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[ORM\Column(name: "url_key", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^[a-z0-9-_]+$/", message: "validation.url")]
    private string $urlKey;

    #[ORM\Column(name: "title", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: "gallery")]
    #[ORM\JoinColumn(name: "page_id", referencedColumnName: "id", nullable: false)]
    private Page $page;

    #[ORM\OneToOne(targetEntity: Image::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: "image_id", referencedColumnName: "id", nullable: false)]
    private ?Image $image = null;

    #[Assert\File(maxSize: "1024k", mimeTypes: ["image/png"], mimeTypesMessage: "validation.image")]
    private ?UploadedFile $file = null;

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrlKey(): string
    {
        return $this->urlKey;
    }

    public function setUrlKey(string $urlKey): void
    {
        $this->urlKey = $urlKey;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function upload(): void
    {
        if ($this->file === null) {
            return;
        }

        $this->image ??= new Image();
        $this->image->setTitle($this->title);
        $this->image->setImage(base64_encode($this->file->getContent()));
    }
}
