<?php

namespace YallaWebsite\BackendBundle\Entity;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class GalleryPreviewRepository extends EntityRepository
{
     protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Find by role key (ROLE_USER, ROLE_ADMIN)
     *
     * @param $role
     * @return Role
     */

    
    public function getPreview() {
        
        $query = $this->entityManager->createQuery("
            select G.id ,  G.name, M preview
            from ApplicationSonataMediaBundle:Gallery G
            left join YallaWebsiteBackendBundle:GalleryPreview GP
            with G.id = GP.gallery
            left join ApplicationSonataMediaBundle:GalleryHasMedia GM
            with GP.media = GM.id
            left join ApplicationSonataMediaBundle:Media M
            with GM.media = M.id
            ORDER BY G.id
            ");
                return $query->getResult();
    } //left OUTER join ApplicationSonataMediaBundle:GalleryHasMedia GM2
//            with GM2.gallery = G.id

    public function getPreviewForGallery($id) {
        
        $query = $this->entityManager->createQuery("
            select M preview
            from ApplicationSonataMediaBundle:Gallery G
            left join YallaWebsiteBackendBundle:GalleryPreview GP
            with G.id = GP.gallery
            left join ApplicationSonataMediaBundle:GalleryHasMedia GM
            with GP.media = GM.id
            left join ApplicationSonataMediaBundle:Media M
            with GM.media = M.id
            where G.id = :id
            ");
                $query->setParameter('id', $id);
                return $query->getResult();
    }
    
    public function findGalleryPreview ($id){
            $query = $this->entityManager->createQuery("
            select GP
            from YallaWebsiteBackendBundle:GalleryPreview GP
            where GP.gallery = :id
            ");
                $query->setParameter('id', $id);
                return $query->getResult();
    }
    
        public function findTasAssigned ($id){
            $query = $this->entityManager->createQuery("
            select GP.tag
            from YallaWebsiteBackendBundle:GalleryPreview GP
            left join YallaWebsiteBackendBundle:Tag T
            with T.id = GP.tag
            where T.name = :id
            ");
                $query->setParameter('id', $id);
                return $query->getResult();
    }

    
    
    
    public function findGallery($id)
            {
        $query = $this->entityManager->createQuery("
            select GP 
            from YallaWebsiteBackendBundle:GalleryPreview GP
            where GP.gallery =:id
            ");
        $query->setParameter('id', $id);

        return $query->getResult();
    }


    ////
//        
//          select G.id, G.name, GM.media_id
//          from ApplicationSonataMediaBundle:Gallery G
//           join YallaWebsiteBackendBundle:GalleryPreview GP
//           join ApplicationSonataMediaBundle:GalleryHasMedia GHM
//           WITH G.id = GP.gallery_id
//           where GP.media_id = GHM.id
//        ");
        
//        
//        $qb = $this->getDoctrine()->createQueryBuilder();
//        return $qb
//                ->select('G.id', 'G.name', 'GM.media_id')
//                ->from('ApplicationSonataMediaBundle:Gallery', 'G')
//                ->join('YallaWebsiteBackendBundle:GalleryPreview', 'GP')
//                ->leftJoin('ApplicationSonataMediaBundle:GalleryHasMedia', 'GHM', 'ON', 'G.id = GP.gallery_id')
//                ->where('GP.media_id = GHM.id')
//                ->getResult();
                 
    }



    
//    select G.id ,  G.name, IDENTITY(GM.media)
//            from ApplicationSonataMediaBundle:Gallery G
//            join YallaWebsiteBackendBundle:GalleryPreview GP
//            with G.id = GP.gallery
//            join ApplicationSonataMediaBundle:GalleryHasMedia GM
//            with GP.media = GM.id