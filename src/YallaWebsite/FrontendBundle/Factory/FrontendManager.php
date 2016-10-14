<?php

/**
 * @author DevKhate <m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\FrontendBundle\Factory;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class FrontendManager
{

    protected $em;
    protected $mediaManager;

    public function __construct(\Doctrine\ORM\EntityManager $em, \Sonata\MediaBundle\Entity\MediaManager $mediaManager)
    {
        $this->em = $em;
        $this->mediaManager = $mediaManager;
    }

    public function getSocialMedia($url)
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

 
//    private function prepareSEO($entity)
//    {
//
//        $seoPage = $this->container->get('sonata.seo.page');
//        $seoPage
//                ->addHeadAttribute('prefix', 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#')
//                ->setTitle('YallaNightLife - ' . $entity->getTitle())
//                ->addMeta('name', 'description', $entity->getMetaDescription())
//                ->addMeta('name', 'keywords', $entity->getMetaTags())
//                ->addMeta('property', 'og:title', $entity->getTitle())
//                ->addMeta('property', 'og:type', $entity->getOgType())
//                ->addMeta('property', 'og:image', $this->getRequest()->getUriForPath($entity->getUrls($this->container->get('sonata.media.provider.image'))))
//                ->addMeta('property', 'og:description', $entity->getMetaDescription())
//                ->addMeta('property', 'og:url', $this->generateUrl('backend_venue_show', array('id' => $entity->getId()), true))
//                ->addMeta('property', 'twitter:description', $entity->getMetaDescription())
//                ->addMeta('property', 'twitter:url', $this->generateUrl('backend_venue_show', array('id' => $entity->getId()), true))
//                ->addMeta('property', 'twitter:card', $entity->getMetaDescription())
//                ->addMeta('property', 'twitter:title', $entity->getTitle())
//                ->addMeta('property', 'twitter:title', $entity->getTitle())
//        ;
//
//        return $seoPage;
//    }

}
