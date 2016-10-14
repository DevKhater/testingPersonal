<?php

namespace YallaWebsite\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use YallaWebsite\FrontendBundle\Model\Homepage as BaseHome;

/**
 * HomePage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class HomePage extends BaseHome
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
     * @ORM\Column(name="slider", type="array")
     */
    protected $sliderEntities;

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
     * @ORM\Column(name="sidearticlesindex", type="integer")
     * @Assert\Range(min=0, max=3)
     */
    protected $sideArticlesIndex = 0;
    /**
     * @ORM\Column(name="sidearticles", type="array")
     */
    protected $sideArticles;

    /**
     * @ORM\Column(name="weekevents", type="array")
     */
    protected $weekEvents = null;
    
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


    public function __construct()
    {
        $this->sliderEntities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sideArticles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->weekEvents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sliderEntities
     *
     * @param array $sliderEntities
     * @return HomePage
     */
    public function addSliderEntities($sliderEntities)
    {
        $this->sliderEntities[] = $sliderEntities;

        return $this;
    }

    public function setSliderEntities($sliderEntities)
    {
        $this->sliderEntities = $sliderEntities;

        return $this;
    }

    /**
     * Get sliderEntities
     *
     * @return array 
     */
    public function getSliderEntities()
    {
        return $this->sliderEntities;
    }

    /**
     * Set videoLink
     *
     * @param string $videoLink
     * @return HomePage
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
     * Set sideArticles
     *
     * @param array $sideArticles
     * @return HomePage
     */
    public function setSideArticles($sideArticles)
    {
        $this->sideArticles = $sideArticles;

        return $this;
    }

    /**
     * Get sideArticles
     *
     * @return array 
     */
    public function getSideArticles()
    {
        return $this->sideArticles;
    }

    /**
     * Set weekEvents
     *
     * @param array $weekEvents
     * @return HomePage
     */
    public function setWeekEvents($weekEvents)
    {
        $this->weekEvents = $weekEvents;

        return $this;
    }

    /**
     * Get weekEvents
     *
     * @return array 
     */
    public function getWeekEvents()
    {
        return $this->weekEvents;
    }

    /**
     * Set selectedGallery
     *
     * @param \Application\Sonata\MediaBundle\Entity\Gallery $selectedGallery
     * @return HomePage
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
     * @return HomePage
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
     * @return HomePage
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

    /**
     * Set about
     *
     * @param string $about
     * @return HomePage
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
     * @return HomePage
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
}
