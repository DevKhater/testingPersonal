<?php

namespace YallaWebsite\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YallaWebsite\FrontendBundle\Form\Type\ContactType;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $hp = $em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $slider = $hp->getSliderEntities();
        $sideArticles = $hp->getSideArticles();
        $weekEvents = $hp->getWeekEvents();
        for ($x = 0; $x <= 4; $x++) {
            $object = $em->getRepository($em->getClassMetadata(get_class($slider[$x]))->getName())->find($slider[$x]->getId());
            $name = 'slider' . $x;
            $$name = $object;
        }
        for ($x = 0; $x <= 6; $x++) {
            $object = $em->getRepository($em->getClassMetadata(get_class($weekEvents[$x]))->getName())->find($weekEvents[$x]->getId());
            $name = 'week' . $x;
            $$name = $object;
        }
        for ($x = 0; $x <= 3; $x++) {
            $object = $em->getRepository($em->getClassMetadata(get_class($sideArticles[$x]))->getName())->find($sideArticles[$x]->getId());
            $name = 'article' . $x;
            $$name = $object;
        }
        $slider_url = $this->getSliderUrl($hp);
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Yalla Night Life Homepage')
                ->addMeta('name', 'description', 'YallaNightLife Homepage')
                ->addMeta('property', 'og:description', 'YallaNightLife Homepage')
                ->addMeta('property', 'twitter:description', 'YallaNightLife Homepage')
                ->addMeta('property', 'og:title', 'Yalla Night Life Homepage')
                ->addMeta('property', 'twitter:title', 'Yalla Night Life Homepage')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/')
        ;
        return $this->render('YallaWebsiteFrontendBundle:Default:index.html.twig', array(
                    'week0' => $week0,'week1' => $week1,'week2' => $week2,
                    'week3' => $week3,'week4' => $week4,'week5' => $week5,
                    'week6' => $week6,'slider0' => $slider0,'slider1' => $slider1,
                    'slider2' => $slider2,'slider3' => $slider3,'slider4' => $slider4,
                    'article0' => $article0,'article1' => $article1,'article2' => $article2,
                    'article3' => $article3,'home' => $hp,'slider_url' => $slider_url));
    }

    public function aboutAction()
    {
        $BEManager = $this->container->get('backend_manager.manager');
        $about = $BEManager->getHomepage()->getAbout();
        $vision = $BEManager->getHomepage()->getVision();
        $members = $this->getDoctrine()->getManager()->getRepository('YallaWebsiteBackendBundle:Member')->findAll();
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('About - Yalla Night Life')
                ->addMeta('name', 'description', 'YallaNightLife About Page')
                ->addMeta('property', 'og:description', 'YallaNightLife About Page')
                ->addMeta('property', 'twitter:description', 'YallaNightLife About Page')
                ->addMeta('property', 'og:title', 'YallaNightLife About Page')
                ->addMeta('property', 'twitter:title', 'YallaNightLife About Page')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/about')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/about')
        ;
        return $this->render('YallaWebsiteFrontendBundle:Page:about.html.twig', array('about' => $about, 'vision' => $vision, 'members' => $members));
    }

    public function venuesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Venue');
        $query = $entities->getLastAll();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 8
            );
        } else {
            $pagination = NULL;
        }
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Guides - Yalla Night Life')
                ->addMeta('name', 'description', 'YallaNightLife Venues Page')
                ->addMeta('property', 'og:description', 'YallaNightLife Venues Page')
                ->addMeta('property', 'twitter:description', 'YallaNightLife Venues Page')
                ->addMeta('property', 'og:title', 'YallaNightLife Venues Page')
                ->addMeta('property', 'twitter:title', 'YallaNightLife Venues Page')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/guides')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/guides');
        return $this->render('YallaWebsiteFrontendBundle:Venue:index.html.twig', array(
                    'pagination' => $pagination
        ));
    }

    public function artistsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Artist');
        $query = $entities->findAll();
        $upcoming = array();
        foreach ($query as $artist) {
            $events = $em->getRepository('YallaWebsiteBackendBundle:Event')->getUpcomigEventsByArtist($artist);
            if ($events)
                $upcoming[$artist->getId()] = $events;
        }
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 8
            );
        } else {
            $pagination = NULL;
        }
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Artists - Yalla Night Life')
                ->addMeta('name', 'description', 'YallaNightLife Artists Page')
                ->addMeta('property', 'og:description', 'YallaNightLife Artists Page')
                ->addMeta('property', 'twitter:description', 'YallaNightLife Artists Page')
                ->addMeta('property', 'og:title', 'YallaNightLife Artists Page')
                ->addMeta('property', 'twitter:title', 'YallaNightLife Artists Page')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/artists')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/artists');
        return $this->render('YallaWebsiteFrontendBundle:Artist:index.html.twig', array(
                    'pagination' => $pagination,
                    'upcoming' => $upcoming
        ));
    }

    public function articlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Article');
        $query = $entities->getLastAll();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 12
            );
        } else {
            $pagination = NULL;
        }
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Gosips - Yalla Night Life')
                ->addMeta('name', 'description', 'YallaNightLife Gossips Page')
                ->addMeta('property', 'og:description', 'YallaNightLife Gossips Page')
                ->addMeta('property', 'twitter:description', 'YallaNightLife Gossips Page')
                ->addMeta('property', 'og:title', 'YallaNightLife Gossips Page')
                ->addMeta('property', 'twitter:title', 'YallaNightLife Gossips Page')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/gossips')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/gossips');
        return $this->render('YallaWebsiteFrontendBundle:Article:index.html.twig', array(
                    'pagination' => $pagination
        ));
    }

    public function getArticleBySlugAction(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Venue Submited to Edit');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Article')->findBySlug($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Venue.');
        }/*
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle($entity->getTitle() .' - Yalla Night Life')
                ->addMeta('name', 'description', $entity->getDescription())
                ->addMeta('property', 'og:description', $entity->getDescription())
                ->addMeta('property', 'twitter:description', $entity->getDescription())
                ->addMeta('property', 'og:title', $entity->getTitle())
                ->addMeta('property', 'twitter:title', $entity->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/gossips')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/gossips');
        */
                $seoPage = $this->getSEOData($entity);
        $seoPage
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com' . $this->generateUrl('yalla_website_frontened_article', array('id' => $entity->getSlug())))
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com' . $this->generateUrl('yalla_website_frontened_article', array('id' => $entity->getSlug())));
        return $this->render('YallaWebsiteFrontendBundle:Article:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    public function eventsAction()
    {
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Events - Yalla Night Life')
                ->addMeta('name', 'description', 'YallaNightLife Events Page')
                ->addMeta('property', 'og:description', 'YallaNightLife Events Page')
                ->addMeta('property', 'twitter:description', 'YallaNightLife Events Page')
                ->addMeta('property', 'og:title', 'YallaNightLife Events Page')
                ->addMeta('property', 'twitter:title', 'YallaNightLife Events Page')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/events')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/events');
        return $this->render('YallaWebsiteFrontendBundle:Events:index.html.twig');
    }

    public function getEventBySlugAction(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Venue Submited to Edit');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Event')->findBySlug($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Venue.');
        }
        $seoPage = $this->getSEOData($entity);
        $seoPage
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com' . $this->generateUrl('yalla_website_frontened_event', array('id' => $entity->getSlug())))
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com' . $this->generateUrl('yalla_website_frontened_event', array('id' => $entity->getSlug())));
        return $this->render('YallaWebsiteFrontendBundle:Events:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    protected function getSEOData($entity)
    {
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle($entity->getTitle() . ' - Yalla Night Life')
                ->addMeta('name', 'description', $entity->getMetaDescription())
                ->addMeta('name', 'keywords', $entity->getMetaTags())
                ->addMeta('property', 'og:title', $entity->getTitle() . ' - Yalla Night Life')
                ->addMeta('property', 'og:type', $entity->getOgType())
                ->addMeta('property', 'og:description', $entity->getMetaDescription())
                //->addMeta('property', 'og:image', $this->getMediaUrl($entity->getMedia()))
                ->addMeta('property', 'twitter:card', $entity->getMetaDescription())
                ->addMeta('property', 'twitter:title', $entity->getTitle() . ' - Yalla Night Life')
                ->addMeta('property', 'twitter:description', $entity->getMetaDescription());
        return $seoPage;
    }

    protected function getMediaUrl($media)
    {
        $provider = $this->container->get($media->getProviderName());
        return $provider->generatePublicUrl($media, 'medium');
    }

    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                        ->setSubject($form->get('subject')->getData())
                        ->setFrom($form->get('email')->getData())
                        ->setTo('info@yallanightlife.com')
                        ->setBody(
                        $this->renderView(
                                'YallaWebsiteFrontendBundle:Mail:contact.html.twig', array(
                            'ip' => $request->getClientIp(),
                            'name' => $form->get('name')->getData(),
                            'message' => $form->get('message')->getData()
                                )
                        )
                );
                $this->get('mailer')->send($message);
                $request->getSession()->getFlashBag()->add('success', 'Your email has been sent! Thanks!');
                return $this->redirect($this->generateUrl('yalla_website_frontend_page_contact'));
            }
        }
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Contact Us - Yalla Night Life')
                ->addMeta('name', 'description', 'YallaNightLife Contact Us Page')
                ->addMeta('property', 'og:description', 'YallaNightLife Contact Us Page')
                ->addMeta('property', 'twitter:description', 'YallaNightLife Contact Us Page')
                ->addMeta('property', 'og:title', 'YallaNightLife Contact Us Page')
                ->addMeta('property', 'twitter:title', 'YallaNightLife Contact Us Page')
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url', 'http://www.yallanightlife.com/contact')
                ->addMeta('property', 'twitter:url', 'http://www.yallanightlife.com/acontact');

        return $this->render('YallaWebsiteFrontendBundle:Page:contact.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    private function getSliderUrl($hp)
    {
        $slider_url = [];
        $url = '';
        $em = $this->getDoctrine()->getManager();

        foreach ($hp->getSliderEntities() as $t) {
            $class_name = $em->getClassMetadata(get_class($t))->getTableName();
            switch ($class_name) {
                case "media__gallery" :
                    $url = $this->generateUrl('sonata_media_gallery_view', array('id' => $t->getSlug()));
                    break;
                case "article" :
                    $url = $this->generateUrl('yalla_website_frontened_article', array('id' => $t->getSlug()));
                    break;
                case "venue" :
                    $url = $this->generateUrl('yalla_website_frontened_venues');
                    break;
                case "events" :
                    $url = $this->generateUrl('yalla_website_frontened_event', array('id' => $t->getSlug()));
                    break;
            }
            array_push($slider_url, $url);
        }
        return $slider_url;
    }

}
