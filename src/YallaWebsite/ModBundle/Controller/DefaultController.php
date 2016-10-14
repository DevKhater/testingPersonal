<?php

namespace YallaWebsite\ModBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use YallaWebsite\ModBundle\Entity\Slide;
use YallaWebsite\ModBundle\Entity\SideArticle;
class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $BEManager = $this->container->get('mod.manager');
        $slider = $BEManager->getSlides();
        $sideArticles = $BEManager->getSideArticles();
        $weekEvents = $BEManager->getWeekEvents();
        //$hp = $BEManager->getHP();
        return $this->render('YallaWebsiteModBundle:Default:index.html.twig', array(
            'slider' => $slider, 
            'sideArt' => $sideArticles ,
            'weekEvents' => $weekEvents ,
            //'content' => $hp,
            ));
    }
}    
