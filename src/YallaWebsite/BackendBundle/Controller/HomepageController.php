<?php

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $BEManager = $this->container->get('backend_manager.manager');
        $M2 = $this->container->get('mod.manager');
        $ddArtciles = $BEManager->getLasts('Article');
        $ddEvent = $BEManager->getLasts('Event');
        $ddVenue = $BEManager->getLasts('Venue');
        $ddGallery = $BEManager->getLasts('Gallery');
        $homepage = $M2->checkHomepage();
        return $this->render('YallaWebsiteBackendBundle:Homepage:index.html.twig', array(
                    'articles' => $ddArtciles,
                    'events' => $ddEvent,
                    'venues' => $ddVenue,
                    'galleries' => $ddGallery,
                    'homepage' => $homepage,
                    'slider' => $M2->getSlides(),
                    'sideArticles' => $M2->getSideArticles(),
                    'weekEvents' => $M2->getWeekEvents(),
        ));
    }

    public function homePageAboutAction()
    {
        $BEManager = $this->container->get('mod.manager');
        $hp = $BEManager->checkHomepage();
        $about = $hp->getAbout();
        $vision = $hp->getVision();
        return $this->render('YallaWebsiteBackendBundle:Homepage:about.html.twig', array(
                    'about' => $about,
                    'vision' => $vision
        ));
    }

    public function setVideoLinkAction(Request $request)
    {
        $id = $request->get('id');
        $BEManager = $this->container->get('mod.manager');
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
        $BEManager = $this->container->get('mod.manager');
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
        $BEManager = $this->container->get('mod.manager');
        if ($BEManager->setFeaturedArticle($id)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getEventsInDayAction(Request $request)
    {
        $id = $request->get('id');
        $BEManager = $this->container->get('mod.manager');
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
        $BEManager = $this->container->get('mod.manager');
        if ($BEManager->setEventsInDay(intval($id), intval($d))) {
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
        $BEManager = $this->container->get('mod.manager');
        $homepage = $BEManager->checkHomepage();
        $homepage->setAbout($data);
        if ($BEManager->saveHomepage($homepage)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function setVisionAction($data)
    {
        $BEManager = $this->container->get('mod.manager');
        $homepage = $BEManager->checkHomepage();
        $homepage->setVision($data);
        if ($BEManager->saveHomepage($homepage)) {
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
        $BEManager = $this->container->get('mod.manager');
        if ($BEManager->setFourArticle((int) $id)) {
            $res = 200;
        } else {
            $res = 500;
        }
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /*     * ********************************************* */

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

}
