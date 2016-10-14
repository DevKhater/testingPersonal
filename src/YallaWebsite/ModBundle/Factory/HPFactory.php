<?php

/**
 * @author DevKhate <m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\ModBundle\Factory;

use YallaWebsite\ModBundle\Entity\Homepage;
use YallaWebsite\ModBundle\Entity\SideArticle;
use YallaWebsite\ModBundle\Entity\SideEvent;

class HPFactory
{

    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getSlides()
    {
        $sliderEntities = new \Doctrine\Common\Collections\ArrayCollection();
        $slides = $this->em->getRepository('YallaWebsiteModBundle:Slide')->getSlider();
        foreach ($slides as $slide) {
            $data = $this->em->getRepository($slide->getEntityType())->find($slide->getEntityID());
            $data->setPublicUrl($slide->getEntityUrl());
            $sliderEntities->add($data);
        }
        return $sliderEntities;
    }

    public function getSideArticles()
    {
        $sideArticles = new \Doctrine\Common\Collections\ArrayCollection();
        $articles = $this->em->getRepository('YallaWebsiteModBundle:SideArticle')->getSideArticles();
        foreach ($articles as $article) {
            $data = $this->em->getRepository('YallaWebsiteBackendBundle:Article')->find($article->getEntityID());
            $sideArticles->add($data);
        }
        return $sideArticles;
    }

    public function getWeekEvents()
    {
        $weekEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $events = $this->em->getRepository('YallaWebsiteModBundle:SideEvent')->getSideEvents();
        foreach ($events as $event) {
            $data = $this->em->getRepository('YallaWebsiteBackendBundle:Event')->find($event->getEntityID());
            $weekEvents->add($data);
        }
        return $weekEvents;
    }

    public function getHP()
    {
        $content = new \Doctrine\Common\Collections\ArrayCollection();
        $hp = $this->em->getRepository('YallaWebsiteModBundle:Homepage')->find(1);
        $content->add($hp->getMainArticle());
        $content->add($hp->getSelectedGallery());
        return $content;
    }

    public function updateVideoLink($id)
    {
        $homepage = $this->checkHomepage();
        $homepage->setVideoLink($id);
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function setFeaturedGallery($id)
    {
        $homepage = $this->checkHomepage();
        $gallery = $this->em->getRepository('ApplicationSonataMediaBundle:Gallery')->find($id);
        $homepage->setSelectedGallery($gallery);
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function setFeaturedArticle($id)
    {
        $homepage = $this->checkHomepage();
        $article = $this->em->getRepository('YallaWebsiteBackendBundle:Article')->find($id);
        $homepage->setMainArticle($article);
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function getEventsInDay($id)
    {
        $events = $this->em->getRepository('YallaWebsiteBackendBundle:Event')->getEventsbyDay($id);
        return $events;
    }

    public function setEventsInDay($id, $d)
    {
        $selectedEvent = $this->em->getRepository('YallaWebsiteBackendBundle:Event')->find($id);
        if ($sideEvent = $this->em->getRepository('YallaWebsiteModBundle:SideEvent')->getPosition($d)) {
            
        } else {
            $sideEvent = new SideEvent($selectedEvent);
        }
        $sideEvent->setPosition(intval($d));
        $this->em->persist($sideEvent);
        $this->em->flush();
    }

    public function checkHomepage()
    {
        if ($hp = $this->em->getRepository('YallaWebsiteModBundle:Homepage')->find(1)) {
            return $hp;
        } else {
            return new Homepage;
        }
    }

    public function saveHomepage(Homepage $hp)
    {
        $this->em->persist($hp);
        $this->em->flush();
    }

    /*     * *************************************************** */

    public function updateSlider($id, $pos, $type)
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $slider = $homepage->getSliderEntities();
        if ($type == 'Gallery') {
            $slider[intval($pos)] = $this->em->getRepository('ApplicationSonataMediaBundle:Gallery')->find(intval($id));
        } else {
            $slider[intval($pos)] = $this->em->getRepository('YallaWebsiteBackendBundle:' . $type)->find(intval($id));
        }
        $homepage->setSliderEntities($this->modifyArray($slider));
        $this->em->persist($homepage);
        $this->em->flush();
        return $homepage;
    }

    public function setFourArticle($id)
    {
        $homepage = $this->checkHomepage();
        $index = $homepage->getSideArticlesIndex();
        if (!($Art = $this->em->getRepository('YallaWebsiteModBundle:SideArticle')->getPosition($index))) {
            $Art = new SideArticle($this->em->getRepository('YallaWebsiteBackendBundle:Article')->find($id));
            $Art->setPosition($index);
        } else {
            $Art->setEntityID($id);
            $Art->setPosition($index);
        }
        $this->em->persist($Art);
        $this->em->flush();

        if ($index >= 0 && $index < 3) {
            $homepage->setSideArticlesIndex($index + 1);
        } else {
            $homepage->setSideArticlesIndex(0);
        }
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function modifyArray($ents)
    {
        $arrr = array();
        foreach ($ents as $ent) {
            $object = $this->em->getRepository($this->em->getClassMetadata(get_class($ent))->getName())->find($ent->getId());
            $arrr[] = $object;
        }
        return $arrr;
    }

    private function prepareSEO($entity)
    {

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
                ->addHeadAttribute('prefix', 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#')
                ->setTitle('YallaNightLife - ' . $entity->getTitle())
                ->addMeta('name', 'description', $entity->getMetaDescription())
                ->addMeta('name', 'keywords', $entity->getMetaTags())
                ->addMeta('property', 'og:title', $entity->getTitle())
                ->addMeta('property', 'og:type', $entity->getOgType())
                ->addMeta('property', 'og:image', $this->getRequest()->getUriForPath($entity->getUrls($this->container->get('sonata.media.provider.image'))))
                ->addMeta('property', 'og:description', $entity->getMetaDescription())
                ->addMeta('property', 'og:url', $this->generateUrl('backend_venue_show', array('id' => $entity->getId()), true))
                ->addMeta('property', 'twitter:description', $entity->getMetaDescription())
                ->addMeta('property', 'twitter:url', $this->generateUrl('backend_venue_show', array('id' => $entity->getId()), true))
                ->addMeta('property', 'twitter:card', $entity->getMetaDescription())
                ->addMeta('property', 'twitter:title', $entity->getTitle())
                ->addMeta('property', 'twitter:title', $entity->getTitle())
        ;

        return $seoPage;
    }

}
