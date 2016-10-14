<?php

namespace YallaWebsite\ModBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use YallaWebsite\ModBundle\Entity\Slide;
class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity2 = $em->getRepository('YallaWebsiteBackendBundle:Article')->find(3);
        $entity3 = $em->getRepository('YallaWebsiteBackendBundle:Article')->find(4);
        $entity1 = $em->getRepository('YallaWebsiteBackendBundle:Article')->find(5);
        $entity4 = $em->getRepository('YallaWebsiteBackendBundle:Event')->find(1);
        $entity0 = $em->getRepository('YallaWebsiteBackendBundle:Event')->find(2);
//        for ($i=0; $i++; $i<5){
//            echo $i+1;
//            if ($em->getRepository('YallaWebsiteModBundle:Slide')->getPosition($i+1)){
//                $name = "entity".$i;
//                $oldSlide = $em->getRepository('YallaWebsiteModBundle:Slide')->findBy(array('position' => $i+1));
//                dump($oldSlide);dump($i);
//                $oldSlide->setPosition($i+1);
//                $oldSlide->setEntityType($em->getClassMetadata(get_class($$name))->getName());
//                $oldSlide->setEntityID($$name->getId());
//                $em->persist($oldSlide);
//                $em->flush($oldSlide);
//            } else {
//                $name = "entity".$i;
//                $Slide = new Slide($em, $$name);
//                //$Slide->setEntityType($this->em->getClassMetadata(get_class($$name))->getName());
//                //$Slide->setEntityID($$name->getId());
//                $Slide->setPosition($i+1);
//                $em->persist($Slide);
//                $em->flush($Slide);
//            }
//        }
        
        $sldier = $em->getRepository('YallaWebsiteModBundle:Slide')->getSlider();
        foreach ($sldier as $slide) {
            $data = $em->getRepository($slide->getEntityType())->find($slide->getEntityID());
            dump($data->getMedia());
        }
        return $this->render('YallaWebsiteModBundle:Default:index.html.twig');
    }
}
