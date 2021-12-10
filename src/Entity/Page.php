<?php

namespace App\Entity;

use App\Model\PageImage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: "page")]
#[UniqueEntity('urlKey')]
class Page
{
    public const IMG_TITLE = 'title';
    public const IMG_BUTTON = 'button';
    public const IMG_BUTTON_HOVER = 'button_hover';
    public const IMG_BACK = 'back';
    public const IMG_BACK_HOVER = 'back_hover';
    public const IMG_DESCRIPTION = 'description';
    public const IMG_CONTENT = 'content';

    public const HOME_URL_KEY = 'home'; // used in template

    #[ORM\Id]
    #[ORM\Column(name: "id", type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[ORM\Column(name: "url_key", type: "string", length: 50, unique: true, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^[a-z0-9-_]+$/", message: "validation.url")]
    private string $urlKey;

    #[ORM\Column(name: "title", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: "childPages")]
    #[ORM\JoinColumn(name: "parent_page_id", referencedColumnName: "id", nullable: true)]
    private ?Page $parentPage = null;

    /**
     * @var Collection<array-key, Page>
     */
    #[ORM\OneToMany(mappedBy: "parentPage", targetEntity: Page::class, orphanRemoval: true)]
    private Collection $childPages;

    #[ORM\Column(name: "row", type: "integer", length: 1, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 9)]
    private ?int $row = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "title_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $titleImg = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "description_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $descriptionImg = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "button_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $buttonImg = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "button_hover_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $buttonImgHover = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "back_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $backImg = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "back_hover_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $backImgHover = null;

    #[ORM\OneToOne(targetEntity: Image::class)]
    #[ORM\JoinColumn(name: "content_img_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Image $content = null;

    /**
     * @var Collection<array-key, Gallery>
     */
    #[ORM\OneToMany(mappedBy: "page", targetEntity: Gallery::class, orphanRemoval: true)]
    private Collection $gallery;

    #[Pure]
    public function __construct()
    {
        $this->childPages = new ArrayCollection();
        $this->gallery = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @return Collection<array-key, Gallery>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    /**
     * @return Collection<array-key, Page>
     */
    public function getChildPagesForRow(int $row): Collection
    {
        return $this->childPages->filter(function (Page $page) use ($row) {
            return $page->getRow() === $row;
        });
    }

    /**
     * @return array<PageImage>
     */
    #[Pure]
    public function getPageImages(): array
    {
        $result = [];
        $result[] = new PageImage(self::IMG_BACK, $this->backImg);
        $result[] = new PageImage(self::IMG_BACK_HOVER, $this->backImgHover);
        $result[] = new PageImage(self::IMG_BUTTON, $this->buttonImg);
        $result[] = new PageImage(self::IMG_BUTTON_HOVER, $this->buttonImgHover);
        $result[] = new PageImage(self::IMG_CONTENT, $this->content);
        $result[] = new PageImage(self::IMG_DESCRIPTION, $this->descriptionImg);
        $result[] = new PageImage(self::IMG_TITLE, $this->titleImg);

        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRow(): int
    {
        return $this->row ?? 1;
    }

    public function setRow(?int $row): void
    {
        $this->row = $row;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getUrlKey(): string
    {
        return $this->urlKey;
    }

    public function setUrlKey(string $urlKey): void
    {
        $this->urlKey = $urlKey;
    }

    public function getParentPage(): ?Page
    {
        return $this->parentPage;
    }

    public function setParentPage(?Page $parentPage): void
    {
        $this->parentPage = $parentPage;
    }

    /**
     * @return Collection<array-key, Page>
     */
    public function getChildPages(): Collection
    {
        return $this->childPages;
    }

    public function getTitleImg(): ?Image
    {
        return $this->titleImg;
    }

    public function setTitleImg(?Image $titleImg): void
    {
        $this->titleImg = $titleImg;
    }

    public function getDescriptionImg(): ?Image
    {
        return $this->descriptionImg;
    }

    public function setDescriptionImg(?Image $descriptionImg): void
    {
        $this->descriptionImg = $descriptionImg;
    }

    public function getButtonImg(): ?Image
    {
        return $this->buttonImg;
    }

    public function setButtonImg(?Image $buttonImg): void
    {
        $this->buttonImg = $buttonImg;
    }

    public function getButtonImgHover(): ?Image
    {
        return $this->buttonImgHover;
    }

    public function setButtonImgHover(?Image $buttonImgHover): void
    {
        $this->buttonImgHover = $buttonImgHover;
    }

    public function getBackImg(): ?Image
    {
        return $this->backImg;
    }

    public function setBackImg(?Image $backImg): void
    {
        $this->backImg = $backImg;
    }

    public function getBackImgHover(): ?Image
    {
        return $this->backImgHover;
    }

    public function setBackImgHover(?Image $backImgHover): void
    {
        $this->backImgHover = $backImgHover;
    }

    public function getContent(): ?Image
    {
        return $this->content;
    }

    public function setContent(?Image $content): void
    {
        $this->content = $content;
    }

    public function setImage(Image $image, string $type): void
    {
        switch ($type) {
            case self::IMG_BACK:
                $this->backImg = $image;

                break;
            case self::IMG_BACK_HOVER:
                $this->backImgHover = $image;

                break;
            case self::IMG_BUTTON:
                $this->buttonImg = $image;

                break;
            case self::IMG_BUTTON_HOVER:
                $this->buttonImgHover = $image;

                break;
            case self::IMG_CONTENT:
                $this->content = $image;

                break;
            case self::IMG_DESCRIPTION:
                $this->descriptionImg = $image;

                break;
            case self::IMG_TITLE:
                $this->titleImg = $image;

                break;
        }
    }
}
