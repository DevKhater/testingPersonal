<?php

/**
 * @author DevKhate <m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\BackendBundle\Factory;

use Symfony\Component\Yaml\Parser;
use YallaWebsite\FrontendBundle\Entity\HomePage;

class BackendManager
{

    protected $em;
    protected $mediaManager;
    protected $homePageId = 1;

    public function __construct(\Doctrine\ORM\EntityManager $em, \Sonata\MediaBundle\Entity\MediaManager $mediaManager)
    {
        $this->em = $em;
        $this->mediaManager = $mediaManager;
    }

    public function saveMedia($entity, $entityContext)
    {
        $entity->getMedia()->setContext($entityContext);
        $this->mediaManager->save($entity->getMedia());
        return $entity;
    }

    public function updateMedia($entity, $oldMedia, $entityContext)
    {
        if (is_null($entity->getMedia())) {
            $entity->setMedia($oldMedia);
        } else {
            $entity = $this->saveMedia($entity, $entityContext);
            $this->deleteMedia($oldMedia);
        }
    }

    public function deleteMedia($media)
    {
        if ($media != NULL) {
            $this->mediaManager->delete($media);
        }
    }

    public function deleteTags($entity)
    {
        if ($entity != NULL) {
            $tags = $entity->getTags();
            foreach ($tags as $tag) {
                $entity->removeTag($tag);
            }
        }
    }

    public function getAdv($url, $id)
    {
        $parser = new Parser();
        try {
            $data = $parser->parse(file_get_contents($url));
        } catch (ParseException $e) {
            throw new ParseException('Unable to parse the YAML string:' . $e->getMessage());
        }

        try {
            return $data = $data[$id];
        } catch (\Exception $e) {
            throw new \Exception('Cannot find `' + $id + '` id in configuration:' . $e->getMessage());
        }
    }

    public function getAllAdv($url)
    {
        $parser = new Parser();
        try {
            $data = $parser->parse(file_get_contents($url));
        } catch (ParseException $e) {
            throw new ParseException('Unable to parse the YAML string:' . $e->getMessage());
        }
        return $data;
    }

    public function saveAdvMedia($media, $oldMediaId)
    {
        $media->setContext('adv');
        $media->setProviderName('sonata.media.provider.image');
        $this->mediaManager->save($media);
        if ($oldMediaId != 0 && !is_null($oldMediaId))
            $this->mediaManager->delete($this->mediaManager->find($oldMediaId));
        return $media;
    }

    public function getLasts($entity)
    {
        if ($entity == 'Gallery') {
            return $this->em->getRepository('ApplicationSonataMediaBundle:' . $entity)->getLastTen();
        }
        return $this->em->getRepository('YallaWebsiteBackendBundle:' . $entity)->getLastTen();
    }

    public function getHomepage()
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $slider = $homepage->getSliderEntities();
        $sideArticles = $homepage->getSideArticles();
        $weekEvents = $homepage->getWeekEvents();

        $homepage->setSliderEntities($this->modifyArray($slider));
        $homepage->setSideArticles($this->modifyArray($sideArticles));
        $homepage->setWeekEvents($this->modifyArray($weekEvents));
        //$this->em->persist($homepage);
        //$this->em->flush();
        return $homepage;
    }

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

    public function updateVideoLink($id)
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $homepage->setVideoLink($id);
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function setFeaturedGallery($id)
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $gallery = $this->em->getRepository('ApplicationSonataMediaBundle:Gallery')->find($id);
        $homepage->setSelectedGallery($gallery);
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function setFeaturedArticle($id)
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $article = $this->em->getRepository('YallaWebsiteBackendBundle:Article')->find($id);
        $homepage->setMainArticle($article);
        $this->em->persist($homepage);
        $this->em->flush();
    }

    public function setFourArticle($id)
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $oldArt = $homepage->getSideArticles();
        $index = $homepage->getSideArticlesIndex();
        if ($index >= 0 && $index < 3) {
            $oldArt[$index] = $this->em->getRepository('YallaWebsiteBackendBundle:Article')->find((int) $id);
            $homepage->setSideArticlesIndex($index + 1);
        } else {
            $oldArt[3] = $this->em->getRepository('YallaWebsiteBackendBundle:Article')->find((int) $id);
            $homepage->setSideArticlesIndex(0);
        }
        $homepage->setSideArticles($this->modifyArray($oldArt));
        $this->em->persist($homepage);
        $this->em->flush();
        return $homepage;
    }

    public function getEventsInDay($id)
    {
        $events = $this->em->getRepository('YallaWebsiteBackendBundle:Event')->getEventsbyDay($id);
        return $events;
    }

    public function setEventsInDay($id, $d)
    {
        $homepage = $this->em->getRepository('YallaWebsiteFrontendBundle:HomePage')->find(1);
        $events = $this->em->getRepository('YallaWebsiteBackendBundle:Event')->find(intval($id));
        $oldWeek = $homepage->getWeekEvents();
        $oldWeek[intval($d)] = $events;
        $homepage->setWeekEvents($oldWeek);
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
