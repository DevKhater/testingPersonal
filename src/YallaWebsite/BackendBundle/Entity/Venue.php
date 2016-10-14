<?php

namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use YallaWebsite\BackendBundle\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Venue
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="YallaWebsite\BackendBundle\Entity\VenueRepository")
 */
class Venue extends BaseEntity
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
     * @ORM\OneToOne(targetEntity="YallaWebsite\BackendBundle\Entity\LocationInformation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $location;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="workingFrom", type="time")
     * 
     */
    private $workingFrom;

    /**
     * @var datetime
     *
     * @ORM\Column(name="workingTo", type="time")
     * 
     */
    private $workingTo;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255)
     * @Assert\Url(
     * message = "The url '{{ value }}' is not a valid url.Format : http://xxx.xxxxxx.xxx",
     * )
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * )
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true,  onDelete="SET NULL")
     */
    protected $media;

    /**
     * @ORM\ManyToMany(targetEntity="YallaWebsite\BackendBundle\Entity\Tag", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="venue_has_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    protected $ogType = 'place';
    protected $metaTags;
    protected $metaDescription;
    protected $url;
    protected $mediaUrl;
    
    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->location = new ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Venue
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
     * Set website
     *
     * @param string $website
     *
     * @return Venue
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Venue
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param MediaInterface $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    public function getMediaId()
    {
        return $this->getMedia()->getId();
    }

    /**
     * @return MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set location
     *
     * @param \YallaWebsite\BackendBundle\Entity\LocationInformation $location
     *
     * @return Venue
     */
    public function setLocation(\YallaWebsite\BackendBundle\Entity\LocationInformation $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \YallaWebsite\BackendBundle\Entity\LocationInformation
     */
    public function getLocation()
    {
        return $this->location;
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Venue
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

    /**
     * Set workingFrom
     *
     * @param \DateTime $workingFrom
     * @return Venue
     */
    public function setWorkingFrom($workingFrom)
    {
        $this->workingFrom = $workingFrom;

        return $this;
    }

    /**
     * Get workingFrom
     *
     * @return \DateTime 
     */
    public function getWorkingFrom()
    {
        return $this->workingFrom;
    }

    /**
     * Set workingTo
     *
     * @param \DateTime $workingTo
     * @return Venue
     */
    public function setWorkingTo($workingTo)
    {
        $this->workingTo = $workingTo;

        return $this;
    }

    /**
     * Get workingTo
     *
     * @return \DateTime 
     */
    public function getWorkingTo()
    {
        return $this->workingTo;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        $this->updatedAt = new \DateTime();
        $this->workingFrom = new \DateTime(date('H:i', $this->workingFrom->getTimestamp()));
        $this->workingTo = new \DateTime(date('H:i', $this->workingTo->getTimestamp()));
    }

}
