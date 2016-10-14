<?php

namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use YallaWebsite\BackendBundle\Entity\SEOBaseEntity;

/** @ORM\MappedSuperclass */ 
abstract class BaseEntity extends SEOBaseEntity
{
    
    
    /** @ORM\Column(type="datetime") */
    protected $updatedAt;

    /** @ORM\Column(type="datetime") */
    protected $createdAt;

    /** @ORM\Column(type="string") 
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=64, unique=true)
     */
    protected $slug;

    
    
    /**
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    
}
