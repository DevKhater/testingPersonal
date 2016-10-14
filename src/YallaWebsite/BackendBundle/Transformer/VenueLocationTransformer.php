<?php
namespace YallaWebsite\BackendBundle\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use YallaWebsite\BackendBundle\Entity\LocationInformation;

class VenueLocationTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }


    public function transform($locationCollection)
    {
        $venuesLocations = $this->manager->getRepository('YallaWebsiteBackendBundle:Venue')->findAll();
        $locations = array();
        foreach ($venuesLocations  as $aVenue) {
            $id = $aVenue->getId();
            $title = $aVenue->getTitle();
            //$_location = $aVenue->getLocation();
            //$_phone  = $_location->getTelephone();
            //$_address  = $_location->getAddress();
            //$value = $_phone.'//'.$_address;
            $locations[$id] = $title;
        }
        return $locations;
    }

    public function reverseTransform($location)
    {
        var_dump($location);exit();
        if (!is_array($location)) return $location;
        $thelocation = new LocationInformation();
        $thelocation->setAddress($location['address']);
        $thelocation->setTelephone($location['telephone']);

        return $thelocation;
    }

}