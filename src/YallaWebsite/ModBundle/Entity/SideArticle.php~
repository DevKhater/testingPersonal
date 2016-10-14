<?php

namespace YallaWebsite\ModBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SideArticle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="YallaWebsite\ModBundle\Entity\SideArticleRepository")
 */
class SideArticle
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
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entityID;
    
    public function __construct($entity)
    {
        $this->entityID = $entity->getId();
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
     * Set position
     *
     * @param integer $position
     * @return SideArticle
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
     * Set entityID
     *
     * @param integer $entityID
     * @return SideArticle
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
}
