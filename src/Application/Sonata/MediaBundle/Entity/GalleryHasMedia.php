<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia as BaseGalleryHasMedia;

/**
 * This file has been generated by the Sonata EasyExtends bundle.
 *
 * @link https://sonata-project.org/bundles/easy-extends
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
use Doctrine\ORM\EntityRepository;

class GalleryHasMedia extends BaseGalleryHasMedia
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function findByGallery($id){
        
        $dql = "
            SELECT e FROM ApplicationSonataMediaBundle:GalleryHasMedia e
            WHERE e.gallery=?1
        ";
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter(1, $id);
        return $query;

//        
//        $qb = $this->createQueryBuilder('p');
//        $qb->where('p.gallery=:id');
//        $qb->setParameter('id', $id);
//        return $qb->getQuery();
    }
}