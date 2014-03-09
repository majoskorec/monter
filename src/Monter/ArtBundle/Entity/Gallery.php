<?php

namespace Monter\ArtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"urlKey", "page"}
 * )
 */
class Gallery
{
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
     * @ORM\Column(name="url_key", type="string", length=50, nullable=false)
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
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="gallery")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false)
     */
    private $image;

    /**
     * Virtual field used for handling the file
     *
     * @var UploadedFile
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/png"},
     *     mimeTypesMessage = "validation.image"
     * )
     */
    private $file;

    /**
     * @return string
     */
    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    private function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads';
    }

    /**
     * @return string
     */
    public function getTempImgName()
    {
        return 'temp.png';
    }

    /**
     * @return string
     */
    public function getTempImgPath()
    {
        return $this->getUploadRootDir() . '/' . $this->getTempImgName();
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getTempImgPath()
        );
        $fileContent = \file_get_contents( $this->getTempImgPath() );

        $image = new Image();
        $image->setTitle( $this->getTitle() )
                ->setImage( \base64_encode( $fileContent ) );

        $this->setImage( $image );
        unlink($this->getTempImgPath());
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

// ---- auto-generated ---------------------------------------------------------


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
     * @return Gallery
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
     * @return Gallery
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
     * Set page
     *
     * @param \Monter\ArtBundle\Entity\Page $page
     * @return Gallery
     */
    public function setPage(\Monter\ArtBundle\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Monter\ArtBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set image
     *
     * @param \Monter\ArtBundle\Entity\Image $image
     * @return Gallery
     */
    public function setImage(\Monter\ArtBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Monter\ArtBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }
}
