<?php

namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArtistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArtistRepository extends EntityRepository
{
    public function findCustomAll()
    {
        $artists = $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from('YallaWebsiteBackendBundle:Artist', 'a')
            ->leftJoin('YallaWebsiteBackendBundle:Event:similarArtist','sm', 'with' , 'sm.similarArtist = a')
            ;
        return $artists->getQuery()->getResult();
        
        
        
        
        
    }
}
