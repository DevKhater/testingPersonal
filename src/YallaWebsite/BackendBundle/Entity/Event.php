<?php

namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use YallaWebsite\BackendBundle\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="YallaWebsite\BackendBundle\Entity\EventRepository")
 */
class Event extends BaseEntity
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
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     * 
     */
    private $title;

    /**
     * @var datetime
     *
     * @ORM\Column(name="startDate", type="datetime")
     * 
     */
    private $startDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="endDate", type="datetime")
     * 
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="is_venue", type="boolean")
     * 
     */
    private $isVenue;

    /**
     * @var string
     * 
     * @ORM\ManyToOne(targetEntity="YallaWebsite\BackendBundle\Entity\Venue", cascade={"persist"})
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id", nullable=true)     * 
     */
    private $venue;

    /**
     * @var string
     * 
     * @ORM\ManyToOne(targetEntity="YallaWebsite\BackendBundle\Entity\LocationInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     * 
     */
    private $location;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $media;

    /**
     * @ORM\ManyToMany(targetEntity="YallaWebsite\BackendBundle\Entity\Tag", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="event_has_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="YallaWebsite\BackendBundle\Entity\Artist", cascade={"persist"})
     * @ORM\JoinTable(name="event_similar_artist",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="artist_id", referencedColumnName="id")}
     *      )
     * 
     */
    protected $similarArtist;

    protected $ogType = 'website';
    
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->location = new ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->similarArtist = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Event
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

    public function getLocationAddress()
    {
        return $this->location->getAddress();
    }

    public function getLocationNumber()
    {
        return $this->location->getTelephone();
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set location
     *
     * @param \YallaWebsite\BackendBundle\Entity\LocationInformation $location
     *
     * @return Event
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
     * @return Event
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
     * Set isVenue
     *
     * @param boolean $isVenue
     * @return Event
     */
    public function setIsVenue($isVenue)
    {
        $this->isVenue = $isVenue;

        return $this;
    }

    /**
     * Get isVenue
     *
     * @return boolean 
     */
    public function getIsVenue()
    {
        return $this->isVenue;
    }

    /**
     * Set venue
     *
     * @param \YallaWebsite\BackendBundle\Entity\Venue $venue
     * @return Event
     */
    public function setVenue(\YallaWebsite\BackendBundle\Entity\Venue $venue = null)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue
     *
     * @return \YallaWebsite\BackendBundle\Entity\Venue 
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        $this->updatedAt = new \DateTime();

        if ($this->isVenue == true) {
            $this->location = null;
        } else {
            $this->isVenue = false;
            $this->venue = null;
        }
    }


    /**
     * Add similarArtist
     *
     * @param \YallaWebsite\BackendBundle\Entity\Artist $similarArtist
     * @return Event
     */
    public function addSimilarArtist(\YallaWebsite\BackendBundle\Entity\Artist $similarArtist)
    {
        $this->similarArtist[] = $similarArtist;

        return $this;
    }

    /**
     * Remove similarArtist
     *
     * @param \YallaWebsite\BackendBundle\Entity\Artist $similarArtist
     */
    public function removeSimilarArtist(\YallaWebsite\BackendBundle\Entity\Artist $similarArtist)
    {
        $this->similarArtist->removeElement($similarArtist);
    }

    /**
     * Get similarArtist
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSimilarArtist()
    {
        return $this->similarArtist;
    }
}
