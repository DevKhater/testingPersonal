<?php 

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YallaWebsite\BackendBundle\Factory\BackendManager;

/**
 * Description of PhotographerController
 *
 * @author Michel Khater
 */
class HomepageController extends Controller
{

    public function homePageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $homepage = $em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $BEManager = $this->container->get('backend_manager.manager');

        $ddArtciles = $BEManager->getLasts('Article');
        $ddEvent = $BEManager->getLasts('Event');
        $ddVenue = $BEManager->getLasts('Venue');
        $ddGallery = $BEManager->getLasts('Gallery');
        return $this->render('YallaWebsiteBackendBundle:Homepage:index.html.twig', array(
                'articles' => $ddArtciles,
                'events' => $ddEvent,
                'venues' => $ddVenue,
                'galleries' => $ddGallery,
                'homepage' => $homepage
        ));
    }

    public function homePageAboutAction()
    {
        $BEManager = $this->container->get('backend_manager.manager');
        $about = $BEManager->getHomepage()->getAbout();
        $vision = $BEManager->getHomepage()->getVision();
        return $this->render('YallaWebsiteBackendBundle:Homepage:about.html.twig', array(
                'about' => $about,
                'vision' => $vision
        ));
    }

    public function setSliderAction(Request $request)
    {
        $id = $request->get('id');
        $pos = $request->get('pos');
        $type = $request->get('type');
        $BEManager = $this->container->get('backend_manager.manager');
        $BEManager->updateSlider($id, $pos, $type);
        //var_dump($BEManager->getHomepage());
        return $this->redirectToRoute('backend_ajax_update_slider_view');
    }

    public function setVideoLinkAction(Request $request)
    {
        $id = $request->get('id');
        $BEManager = $this->container->get('backend_manager.manager');
        if ($BEManager->updateVideoLink($id)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function updateSliderViewAction()
    {
        $BEManager = $this->container->get('backend_manager.manager');
        $template = $this->render(
                'YallaWebsiteBackendBundle:Homepage:html\slider_preview.html.twig', array('homepage' => $BEManager->getHomepage()))->getContent();
        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setFeaturedGalleryAction(Request $request)
    {
        $id = $request->get('id');
        $BEManager = $this->container->get('backend_manager.manager');
        if ($BEManager->setFeaturedGallery($id)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setFeaturedArticleAction(Request $request)
    {
        $id = $request->get('id');
        $BEManager = $this->container->get('backend_manager.manager');
        if ($BEManager->setFeaturedArticle($id)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setFourArticlesAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $homepage = $em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $oldArt = $homepage->getSideArticles();
        $index = $homepage->getSideArticlesIndex();
        if ($index != 3) {
            $oldArt[$index] = $em->getRepository('YallaWebsiteBackendBundle:Article')->find((int)$id);
            $homepage->setSideArticlesIndex($index + 1);
        } else {
            $oldArt[3] = $em->getRepository('YallaWebsiteBackendBundle:Article')->find((int) $id);
            $homepage->setSideArticlesIndex(0);
        }
        $homepage->setSideArticles($oldArt);
        $em->persist($homepage);
        $em->flush();



//        $BEManager = $this->container->get('backend_manager.manager');
//        if ($BEManager->setFourArticle($id)) {
//            $res = 200;
//        } else {
//            $res = 500;
//        }
        $response = new Response(200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getEventsInDayAction(Request $request)
    {
        $id = $request->get('id');
        $BEManager = $this->container->get('backend_manager.manager');
        $events = $BEManager->getEventsInDay($id);
        if ($events) {
            $res = 200;
        } else {
            $res = 500;
        }
        $template = $this->render(
                'YallaWebsiteBackendBundle:Homepage:html\dropdown_day_event.html.twig', array('events' => $events))->getContent();
        $json = json_encode($template);
        $response = new Response($json, $res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setEventsInDayAction(Request $request)
    {
        $id = $request->get('id');
        $d = $request->get('d');
        $BEManager = $this->container->get('backend_manager.manager');
        if ($BEManager->setEventsInDay($id, $d)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setAboutAction($data)
    {
        $BEManager = $this->container->get('backend_manager.manager');
        $homepage = $BEManager->getHomepage()->setAbout($data);
        $em = $this->getDoctrine()->getManager();
        $em->persist($homepage);
        $em->flush();
        $response = new Response(200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setVisionAction($data)
    {
        $BEManager = $this->container->get('backend_manager.manager');
        $homepage = $BEManager->getHomepage()->setVision($data);
        $em = $this->getDoctrine()->getManager();
        $em->persist($homepage);
        $em->flush();
        $response = new Response(200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
