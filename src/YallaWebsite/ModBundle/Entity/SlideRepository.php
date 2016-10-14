<?php

namespace YallaWebsite\ModBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SlideRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SlideRepository extends EntityRepository
{
    public function getSlider()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM YallaWebsiteModBundle:Slide s WHERE s.position IN(1,2,3,4,5) ORDER BY s.position ASC'
            )
            ->getResult();
    }

    public function getPosition($pos)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM YallaWebsiteModBundle:Slide s WHERE s.position = :pos'
            )
                ->setParameter('pos', $pos)
                ->getResult();
    }
}