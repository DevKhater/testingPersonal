<?php

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery as BaseGallery;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;

class Gallery extends BaseGallery //implements Taggable
{

    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var text $description
     */
    protected $description;
    private $media;
    protected $tags;
    private $photographers;
    private $slug;

    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photographers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     *
     * @return Gallery
     */
    public function addGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedias[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedias->removeElement($galleryHasMedia);
    }
    /**
     * Add tag
     *
     * @param \DCS\TagBundle\Model\TagInterface $tag
     * @return Post
     */
    public function addTag(\DCS\TagBundle\Model\TagInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \DCS\TagBundle\Model\TagInterface $tag
     */
    public function removeTag(\DCS\TagBundle\Model\TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $media
     *
     * @return Gallery
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\GalleryHasMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Gallery
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @var \DateTime
     */
    private $date;


    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Gallery
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Gallery
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function getPhotographers()
    {
        return $this->photographers;
    }

   public function hasPhotographer(\YallaWebsite\BackendBundle\Entity\Photographer $photographer)
    {
        return $this->getPhotographers()->contains($photographer);
    }

    public function removePhotographer(\YallaWebsite\BackendBundle\Entity\Photographer $photographers)
    {
        $this->photographers->removeElement($photographers);
    }


    public function getLastAll()
    {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT a FROM YallaWebsiteBackendBundle:Article a ORDER BY a.createdAt DESC')
                        ->getResult();
    }



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        $this->updatedAt = new \DateTime();
        $this->date = new \DateTime(date('Y-m-d', $this->date->getTimestamp()));
    }

}
