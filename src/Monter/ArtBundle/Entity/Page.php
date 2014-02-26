<?php

namespace Monter\ArtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity
 * @UniqueEntity("urlKey")
 */
class Page
{
    const IMG_TITLE = 'title';
    const IMG_BUTTON = 'button';
    const IMG_BUTTON_HOVER = 'button_hover';
    const IMG_BACK = 'back';
    const IMG_BACK_HOVER = 'back_hover';
    const IMG_DESCRIPTION = 'description';
    const IMG_CONTENT = 'content';

    const HOME_URL_KEY = 'home';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url_key", type="string", length=50, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern = "/^[a-z0-9-_]+$/", message = "validation.url")
     */
    private $urlKey;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var \Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="childPages")
     * @ORM\JoinColumn(name="parent_page_id", referencedColumnName="id", nullable=true)
     */
    private $parentPage;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parentPage", orphanRemoval=true)
     */
    private $childPages;

    /**
     * @var int
     *
     * @ORM\Column(name="row", type="integer", length=1, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 9,
     *      minMessage = "validation.row",
     *      maxMessage = "validation.row"
     * )
     */
    private $row = 1;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="title_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $titleImg;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="description_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $descriptionImg;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="button_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $buttonImg;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="button_hover_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $buttonImgHover;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="back_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $backImg;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="back_hover_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $backImgHover;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="content_img_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $content;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param int $row
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildPagesForRow($row)
    {
        return $this->getChildPages()->filter(function (Page $page) use ($row) {
            return $page->getRow() == $row;
        });
    }

    /**
     * @return array
     */
    public function getPageImages()
    {
        $result = array();
        $result[self::IMG_BACK] = array(
            'isSet' => !is_null( $this->backImg ),
            'id' => !is_null( $this->backImg ) ? $this->backImg->getId() : null,
            'title' => !is_null( $this->backImg ) ? $this->backImg->getTitle() : null,
            'data' => !is_null( $this->backImg ) ? $this->backImg->getImage() : null,
        );
        $result[self::IMG_BACK_HOVER] = array(
            'isSet' => !is_null( $this->backImgHover ),
            'id' => !is_null( $this->backImgHover ) ? $this->backImgHover->getId() : null,
            'title' => !is_null( $this->backImgHover ) ? $this->backImgHover->getTitle() : null,
            'data' => !is_null( $this->backImgHover ) ? $this->backImgHover->getImage() : null,
        );
        $result[self::IMG_BUTTON] = array(
            'isSet' => !is_null( $this->buttonImg ),
            'id' => !is_null( $this->buttonImg ) ? $this->buttonImg->getId() : null,
            'title' => !is_null( $this->buttonImg ) ? $this->buttonImg->getTitle() : null,
            'data' => !is_null( $this->buttonImg ) ? $this->buttonImg->getImage() : null,
        );
        $result[self::IMG_BUTTON_HOVER] = array(
            'isSet' => !is_null( $this->buttonImgHover ),
            'id' => !is_null( $this->buttonImgHover ) ? $this->buttonImgHover->getId() : null,
            'title' => !is_null( $this->buttonImgHover ) ? $this->buttonImgHover->getTitle() : null,
            'data' => !is_null( $this->buttonImgHover ) ? $this->buttonImgHover->getImage() : null,
        );
        $result[self::IMG_CONTENT] = array(
            'isSet' => !is_null( $this->content ),
            'id' => !is_null( $this->content ) ? $this->content->getId() : null,
            'title' => !is_null( $this->content ) ? $this->content->getTitle() : null,
            'data' => !is_null( $this->content ) ? $this->content->getImage() : null,
        );
        $result[self::IMG_DESCRIPTION] = array(
            'isSet' => !is_null( $this->descriptionImg ),
            'id' => !is_null( $this->descriptionImg ) ? $this->descriptionImg->getId() : null,
            'title' => !is_null( $this->descriptionImg ) ? $this->descriptionImg->getTitle() : null,
            'data' => !is_null( $this->descriptionImg ) ? $this->descriptionImg->getImage() : null,
        );
        $result[self::IMG_TITLE] = array(
            'isSet' => !is_null( $this->titleImg ),
            'id' => !is_null( $this->titleImg ) ? $this->titleImg->getId() : null,
            'title' => !is_null( $this->titleImg ) ? $this->titleImg->getTitle() : null,
            'data' => !is_null( $this->titleImg ) ? $this->titleImg->getImage() : null,
        );
        return $result;
    }

// ---- auto-generated ---------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childPages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set urlKey
     *
     * @param string $urlKey
     * @return Page
     */
    public function setUrlKey($urlKey)
    {
        $this->urlKey = $urlKey;

        return $this;
    }

    /**
     * Get urlKey
     *
     * @return string 
     */
    public function getUrlKey()
    {
        return $this->urlKey;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set row
     *
     * @param integer $row
     * @return Page
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get row
     *
     * @return integer 
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Set parentPage
     *
     * @param \Monter\ArtBundle\Entity\Page $parentPage
     * @return Page
     */
    public function setParentPage(\Monter\ArtBundle\Entity\Page $parentPage = null)
    {
        $this->parentPage = $parentPage;

        return $this;
    }

    /**
     * Get parentPage
     *
     * @return \Monter\ArtBundle\Entity\Page 
     */
    public function getParentPage()
    {
        return $this->parentPage;
    }

    /**
     * Add childPages
     *
     * @param \Monter\ArtBundle\Entity\Page $childPages
     * @return Page
     */
    public function addChildPage(\Monter\ArtBundle\Entity\Page $childPages)
    {
        $this->childPages[] = $childPages;

        return $this;
    }

    /**
     * Remove childPages
     *
     * @param \Monter\ArtBundle\Entity\Page $childPages
     */
    public function removeChildPage(\Monter\ArtBundle\Entity\Page $childPages)
    {
        $this->childPages->removeElement($childPages);
    }

    /**
     * Get childPages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildPages()
    {
        return $this->childPages;
    }

    /**
     * Set titleImg
     *
     * @param \Monter\ArtBundle\Entity\Image $titleImg
     * @return Page
     */
    public function setTitleImg(\Monter\ArtBundle\Entity\Image $titleImg = null)
    {
        $this->titleImg = $titleImg;

        return $this;
    }

    /**
     * Get titleImg
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getTitleImg()
    {
        return $this->titleImg;
    }

    /**
     * Set descriptionImg
     *
     * @param \Monter\ArtBundle\Entity\Image $descriptionImg
     * @return Page
     */
    public function setDescriptionImg(\Monter\ArtBundle\Entity\Image $descriptionImg = null)
    {
        $this->descriptionImg = $descriptionImg;

        return $this;
    }

    /**
     * Get descriptionImg
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getDescriptionImg()
    {
        return $this->descriptionImg;
    }

    /**
     * Set buttonImg
     *
     * @param \Monter\ArtBundle\Entity\Image $buttonImg
     * @return Page
     */
    public function setButtonImg(\Monter\ArtBundle\Entity\Image $buttonImg = null)
    {
        $this->buttonImg = $buttonImg;

        return $this;
    }

    /**
     * Get buttonImg
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getButtonImg()
    {
        return $this->buttonImg;
    }

    /**
     * Set buttonImgHover
     *
     * @param \Monter\ArtBundle\Entity\Image $buttonImgHover
     * @return Page
     */
    public function setButtonImgHover(\Monter\ArtBundle\Entity\Image $buttonImgHover = null)
    {
        $this->buttonImgHover = $buttonImgHover;

        return $this;
    }

    /**
     * Get buttonImgHover
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getButtonImgHover()
    {
        return $this->buttonImgHover;
    }

    /**
     * Set backImg
     *
     * @param \Monter\ArtBundle\Entity\Image $backImg
     * @return Page
     */
    public function setBackImg(\Monter\ArtBundle\Entity\Image $backImg = null)
    {
        $this->backImg = $backImg;

        return $this;
    }

    /**
     * Get backImg
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getBackImg()
    {
        return $this->backImg;
    }

    /**
     * Set backImgHover
     *
     * @param \Monter\ArtBundle\Entity\Image $backImgHover
     * @return Page
     */
    public function setBackImgHover(\Monter\ArtBundle\Entity\Image $backImgHover = null)
    {
        $this->backImgHover = $backImgHover;

        return $this;
    }

    /**
     * Get backImgHover
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getBackImgHover()
    {
        return $this->backImgHover;
    }

    /**
     * Set content
     *
     * @param \Monter\ArtBundle\Entity\Image $content
     * @return Page
     */
    public function setContent(\Monter\ArtBundle\Entity\Image $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getContent()
    {
        return $this->content;
    }
}
