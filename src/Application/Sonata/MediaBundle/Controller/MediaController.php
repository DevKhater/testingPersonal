<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Controller;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MediaController extends Controller
{
    /**
     * @param MediaInterface $media
     *
     * @return MediaProviderInterface
     */
    public function getProvider(MediaInterface $media)
    {
        return $this->get('sonata.media.pool')->getProvider($media->getProviderName());
    }

    /**
     * @param string $id
     *
     * @return MediaInterface
     */
    public function getMedia($id)
    {
        return $this->get('sonata.media.manager.media')->find($id);
    }

    /**
     * @throws NotFoundHttpException
     *
     * @param string $id
     * @param string $format
     *
     * @return Response
     */
    public function downloadAction($id, $format = 'reference')
    {
        $media = $this->getMedia($id);

        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }

        if (!$this->get('sonata.media.pool')->getDownloadSecurity($media)->isGranted($media, $this->getRequest())) {
            throw new AccessDeniedException();
        }

        $response = $this->getProvider($media)->getDownloadResponse($media, $format, $this->get('sonata.media.pool')->getDownloadMode($media));

        if ($response instanceof BinaryFileResponse) {
            $response->prepare($this->get('request'));
        }

        return $response;
    }

    protected function getMediaUrl($media)
    {
        $provider = $this->container->get($media->getProviderName());
        return $provider->generatePublicUrl($media, 'small');
    }

    /**
     * @throws NotFoundHttpException
     *
     * @param string $id
     * @param string $format
     *
     * @return Response
     */
    public function viewAction($gid, $id, $format = 'reference')
    {
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'slug'      => $gid,
            'enabled' => true,
        ));

        $media = $this->getMedia($id);

        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle($gallery->getName() .' Snapshots  - Yalla Night Life')
        ->addMeta('name', 'description', $gallery->getDescription())
            ->addMeta('name', 'keywords', 'Yalla, Night, Life, ' . $gallery->getName().' , Galleries, Snapshots, Events, Images, Nightlife')
            ->addMeta('property', 'og:title', $gallery->getName() . ' - Yalla Night Life')
            ->addMeta('property', 'og:description', $gallery->getDescription())
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:image',  $this->getMediaUrl($media))
            ->addMeta('property', 'og:url',  'http://www.yallanightlife.com/snapshots/view/'. $gid)
            ->addMeta('property', 'twitter:url',  'http://www.yallanightlife.com/snapshots/view/'. $gid)
            ->addMeta('property', 'twitter:title',  'Snapshots - Yalla Night Life')
            ->addMeta('property', 'twitter:description', $gallery->getDescription());

        return $this->render('SonataMediaBundle:Gallery:singleView.html.twig', array(
                'media' => $media,
                'gallery' => $gallery
            ));
    }
}



