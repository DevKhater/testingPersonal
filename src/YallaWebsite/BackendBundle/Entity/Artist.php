<?php namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Artist
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="YallaWebsite\BackendBundle\Entity\ArtistRepository")
 */
class Artist
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="text")
     */
    private $biography;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true,  onDelete="SET NULL")
     */
    protected $media;

    /**
     * @ORM\OneToOne(targetEntity="YallaWebsite\BackendBundle\Entity\SocialMedia", cascade={"persist"})
     * @ORM\JoinColumn(name="sm_id", referencedColumnName="id", nullable=true,  onDelete="SET NULL")
     */
    protected $sm;

    /** @ORM\Column(type="string") 
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=64, unique=true)
     */
    protected $slug;

    /**
     * Get id
     *
     * @return integer 
     */
    
    protected $ogType = 'profile';
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Artist
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
     * Set biography
     *
     * @param string $biography
     * @return Artist
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string 
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Artist
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return Artist
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set sm
     *
     * @param \YallaWebsite\BackendBundle\Entity\SocialMedia $sm
     * @return Artist
     */
    public function setSm(\YallaWebsite\BackendBundle\Entity\SocialMedia $sm = null)
    {
        $this->sm = $sm;

        return $this;
    }

    /**
     * Get sm
     *
     * @return \YallaWebsite\BackendBundle\Entity\SocialMedia 
     */
    public function getSm()
    {
        return $this->sm;
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
}
