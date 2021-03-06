<?php

namespace YallaWebsite\ModBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SideEventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SideEventRepository extends EntityRepository
{
    public function getSideEvents()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM YallaWebsiteModBundle:SideEvent s WHERE s.position IN(1,2,3,4,5,6,7) ORDER BY s.position ASC'
            )
            ->getResult();
    }

    public function getPosition($pos)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM YallaWebsiteModBundle:SideEvent s WHERE s.position = :pos'
            )
                ->setParameter('pos', $pos)
                ->getOneOrNullResult();
    }
}
