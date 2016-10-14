<?php

namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * SocialMedia
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SocialMedia
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
     * @ORM\Column(name="socialMediaData", type="array")
     */
    protected $socialMediaData;


    public function __construct()
    {
        $this->socialMediaData = array(
            'facebook' => null,
            'youtube' => null,
            'googleplus' => null,
            'twitter' => null,
            'instagram' => null,
            'soundcloud' => null
        );
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
     * Set socialMediaData
     *
     * @param array $socialMediaData
     * @return SocialMedia
     */
    public function setSocialMediaData($socialMediaData)
    {
        $this->socialMediaData = $socialMediaData;
    }

    /**
     * Get socialMediaData
     *
     * @return array 
     */
    public function getSocialMediaData()
    {
        return $this->socialMediaData;
    }
    
    public function setFacebook ($socialMediaData)
    {
        $this->socialMediaData['facebook'] = $socialMediaData;

    }
    public function setGoogleplus ($socialMediaData)
    {
        $this->socialMediaData['googleplus'] = $socialMediaData;

    }
    public function setTwitter ($socialMediaData)
    {
        $this->socialMediaData['twitter'] = $socialMediaData;

    }
    public function setInstagram ($socialMediaData)
    {
        $this->socialMediaData['instagram'] = $socialMediaData;

    }
    public function setSoundCloud ($socialMediaData)
    {
        $this->socialMediaData['soundcloud'] = $socialMediaData;

    }
    public function setYouTube ($socialMediaData)
    {
        $this->socialMediaData['youtube'] = $socialMediaData;
    }

    public function getFacebook ()
    {
        return $this->socialMediaData['facebook'];

    }
    public function getGoogleplus ()
    {
        return $this->socialMediaData['googleplus'];

    }
    public function getTwitter ()
    {
        return $this->socialMediaData['twitter'];

    }
    public function getInstagram ()
    {
        return $this->socialMediaData['instagram'];

    }
    public function getSoundCloud ()
    {
        return $this->socialMediaData['soundcloud'];

    }
    public function getYouTube ()
    {
        return $this->socialMediaData['youtube'];
    }
}
