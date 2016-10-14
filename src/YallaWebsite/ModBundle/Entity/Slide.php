<?php

namespace YallaWebsite\ModBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slide
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="YallaWebsite\ModBundle\Entity\SlideRepository")
 */
class Slide
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
     * @var integer
     *
     * @ORM\Column(name="type", type="string")
     */
    private $entityType;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entityID;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_url", type="string")
     */
    private $entityUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="slide_pos", type="integer")
     */
    private $position;

    public function __construct($em, $entity)
    {
        $this->em = $em;
        $this->entityType = $this->em->getClassMetadata(get_class($entity))->getName();
        $this->entityID = $entity->getId();
        switch ($this->entityType) {
            case "Application\Sonata\MediaBundle\Entity\Gallery" :
                $this->entityUrl = "/snapshots/view/" . $entity->getSlug();
                break;
            case "YallaWebsite\BackendBundle\Entity\Article" :
                $this->entityUrl = "/gossip/" . $entity->getSlug();
                break;
            case "YallaWebsite\BackendBundle\Entity\Venue" :
                $this->entityUrl = "/guides/";
                break;
            case "YallaWebsite\BackendBundle\Entity\Event" :
                $this->entityUrl = "/event/" . $entity->getSlug();
                break;
        }
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
     * Set entityType
     *
     * @param string $entityType
     * @return Slide
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return string 
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set entityID
     *
     * @param integer $entityID
     * @return Slide
     */
    public function setEntityID($entityID)
    {
        $this->entityID = $entityID;

        return $this;
    }

    /**
     * Get entityID
     *
     * @return integer 
     */
    public function getEntityID()
    {
        return $this->entityID;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Slide
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }


    /**
     * Set entityUrl
     *
     * @param string $entityUrl
     * @return Slide
     */
    public function setEntityUrl($entityUrl)
    {
        $this->entityUrl = $entityUrl;

        return $this;
    }

    /**
     * Get entityUrl
     *
     * @return string 
     */
    public function getEntityUrl()
    {
        return $this->entityUrl;
    }
}
