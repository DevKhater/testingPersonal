<?php

namespace YallaWebsite\ModBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Homepage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="YallaWebsite\ModBundle\Entity\HomepageRepository")
 */
class Homepage
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
     * @ORM\Column(name="videolink", type="string", length=255, nullable=true)
     * )
     */
    protected $videoLink;

    /**
     * @var gallery
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Gallery", cascade={"persist"})
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=true)
     */
    protected $selectedGallery;

    /**
     * @var article
     * 
     * @ORM\ManyToOne(targetEntity="YallaWebsite\BackendBundle\Entity\Article", cascade={"persist"})
     * @ORM\JoinColumn(name="mainarticle_id", referencedColumnName="id", nullable=true)
     */
    protected $mainArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true, options={"default" = null})
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="vision", type="text", nullable=true, options={"default" = null})
     */
    private $vision;
    
    /**
     * @ORM\Column(name="sidearticlesindex", type="integer")
     * @Assert\Range(min=0, max=3)
     */
    protected $sideArticlesIndex = 0;
    
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
     * Set videoLink
     *
     * @param string $videoLink
     * @return Homepage
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    /**
     * Get videoLink
     *
     * @return string 
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return Homepage
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set vision
     *
     * @param string $vision
     * @return Homepage
     */
    public function setVision($vision)
    {
        $this->vision = $vision;

        return $this;
    }

    /**
     * Get vision
     *
     * @return string 
     */
    public function getVision()
    {
        return $this->vision;
    }

    /**
     * Set selectedGallery
     *
     * @param \Application\Sonata\MediaBundle\Entity\Gallery $selectedGallery
     * @return Homepage
     */
    public function setSelectedGallery(\Application\Sonata\MediaBundle\Entity\Gallery $selectedGallery = null)
    {
        $this->selectedGallery = $selectedGallery;

        return $this;
    }

    /**
     * Get selectedGallery
     *
     * @return \Application\Sonata\MediaBundle\Entity\Gallery 
     */
    public function getSelectedGallery()
    {
        return $this->selectedGallery;
    }

    /**
     * Set mainArticle
     *
     * @param \YallaWebsite\BackendBundle\Entity\Article $mainArticle
     * @return Homepage
     */
    public function setMainArticle(\YallaWebsite\BackendBundle\Entity\Article $mainArticle = null)
    {
        $this->mainArticle = $mainArticle;

        return $this;
    }

    /**
     * Get mainArticle
     *
     * @return \YallaWebsite\BackendBundle\Entity\Article 
     */
    public function getMainArticle()
    {
        return $this->mainArticle;
    }

    /**
     * Set sideArticlesIndex
     *
     * @param integer $sideArticlesIndex
     * @return Homepage
     */
    public function setSideArticlesIndex($sideArticlesIndex)
    {
        $this->sideArticlesIndex = $sideArticlesIndex;

        return $this;
    }

    /**
     * Get sideArticlesIndex
     *
     * @return integer 
     */
    public function getSideArticlesIndex()
    {
        return $this->sideArticlesIndex;
    }
}
