<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryController extends Controller
{
    /**
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationSonataMediaBundle:Gallery');
        $query = $entities->getAllByDate();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 12
            );
        } else {
            $pagination = NULL;
        }
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Snapshots - Yalla Night Life')
            ->addMeta('name', 'keywords', 'Yalla, Night, Life, Galleries, Snapshots, Events, Images, Nightlife')
            ->addMeta('property', 'og:title', 'Snapshots - Yalla Night Life')
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:url',  'http://www.yallanightlife.com/snapshots')
            ->addMeta('property', 'twitter:url',  'http://www.yallanightlife.com/snapshots')
            ->addMeta('property', 'twitter:title',  'Snapshots - Yalla Night Life');
        return $this->render('SonataMediaBundle:Gallery:index.html.twig', array(
            'pagination' => $pagination

        ));
    }

    /**
     * @param string $id
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function viewAction($id)
    {
        $id = rtrim($id, '/');
        $em = $this->getDoctrine()->getManager();
        $galleryId = $em->getRepository('ApplicationSonataMediaBundle:Gallery')->getIdFromSlug($id);
        $images = $em->getRepository('ApplicationSonataMediaBundle:GalleryHasMedia');
        $query = $images->findByGallery($galleryId);
        if (!$query) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 75
            );
        } else {
            $pagination = NULL;
        }

        $keys = '';
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'slug'      => $id,
            'enabled' => true,
        ));
        foreach ($gallery->getTags() as $t) {$keys .= $t->getName().' ,';}
        $media = $gallery->getMedia();
        $provider = $this->container->get($media->getProviderName());
        $url = $provider->generatePublicUrl($media, 'small');
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle($gallery->getName() .' - Yalla Night Life')
            ->addMeta('name', 'description', mb_substr($gallery->getDescription(), 0, 160))
            ->addMeta('name', 'keywords', mb_substr($keys, 0, 250))
            ->addMeta('property', 'og:title', $gallery->getName() .' - Yalla Night Life')
            ->addMeta('property', 'og:description', mb_substr($gallery->getDescription(), 0, 160))
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:url',  'http://www.yallanightlife.com'.$this->generateUrl('sonata_media_gallery_view', array('id' => $gallery->getSlug())))
            ->addMeta('property', 'og:image',  $url)
            ->addMeta('property', 'twitter:card',  mb_substr($gallery->getDescription(), 0, 160))
            ->addMeta('property', 'twitter:url',  'http://www.yallanightlife.com'.$this->generateUrl('sonata_media_gallery_view', array('id' => $gallery->getSlug())))
            ->addMeta('property', 'twitter:title',  $gallery->getName() .' - Yalla Night Life')
            ->addMeta('property', 'twitter:description',  mb_substr($gallery->getDescription(), 0, 160));
        return $this->render('SonataMediaBundle:Gallery:view.html.twig', array(
            'gallery'   => $gallery,
            'pagination' => $pagination
        ));
    }

    public function loadMoreAction()
    {

    }
}
