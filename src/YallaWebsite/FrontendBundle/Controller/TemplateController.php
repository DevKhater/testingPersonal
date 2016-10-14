<?php namespace YallaWebsite\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use YallaWebsite\FrontendBundle\Entity\HomePage;

class TemplateController extends Controller
{

    public function socialAction()
    {
        $socialData = $this->container->getParameter('socialmedia');
        return $this->render('YallaWebsiteFrontendBundle:Template:socialmedia.html.twig', array(
                'content' => $socialData
        ));
    }

    public function aboutAction()
    {
        return $this->render('YallaWebsiteFrontendBundle:Page:about.html.twig');
    }

    public function homepageAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$hp= $em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        //$oldArt = $homepage->getSideArticles();
        //$oldArt[3] = $em->getRepository('YallaWebsiteBackendBundle:Article')->find(2);
        //$homepage->setSideArticlesIndex(0);
        //$homepage->setSideArticles($oldArt);
        //$ent->setSelectedGallery($em->getRepository('ApplicationSonataMediaBundle:Gallery')->find(5));
        //$hm = $em->getRepository('YallaWebsiteBackendBundle:Event')->getEventsbyDay(1);
        $hp = new HomePage();
//        $hp->setSliderEntities(NULL);
//        for ($i = 0 ; $i <3 ; $i++)
//        {
//            $hp->addSliderEntities($em->getRepository('YallaWebsiteBackendBundle:Event')->find(1));
//        }
//        for ($i = 0 ; $i <2 ; $i++)
//        {
//            $hp->addSliderEntities($em->getRepository('YallaWebsiteBackendBundle:Venue')->find(1));
//        }

//        $hp->addSliderEntities($em->getRepository('YallaWebsiteBackendBundle:Event')->find(4));
//        $hp->addSliderEntities($em->getRepository('YallaWebsiteBackendBundle:Event')->find(5));
//        $hp->addSliderEntities($em->getRepository('YallaWebsiteBackendBundle:Venue')->find(1));
//        $hp->addSliderEntities($em->getRepository('YallaWebsiteBackendBundle:Article')->find(1));
//        $hp->addSliderEntities($em->getRepository('ApplicationSonataMediaBundle:Gallery')->find(1));
        
        $em->persist($hp);
        $em->flush();

        dump($hp);
        exit;
    }
}
