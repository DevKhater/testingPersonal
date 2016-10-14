<?php namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
abstract class SEOBaseEntity
{

    protected $metaTags;
    protected $metaDescription;
    protected $url;
    protected $mediaUrl;
    protected $ogType;

    public function __construct()
    {
        $this->metaTags = $this->getMetaTags();
        $this->metaDescription = $this->getMetaDescription();
        $this->mediaUrl = $this->getUrls();
    }

    public function getMetaTags()
    {
        $tags = $this->getTags();
        if ($tags != null) {
            foreach ($tags as $tag) {
                $this->metaTags .= $tag->getName() . ', ';
            }
        } else {
            $this->metaTags = '';
        }
        return $this->metaTags;
    }

    public function getMetaDescription()
    {
        $content = $this->getContent();
        $this->metaDescription = mb_substr($content, 0, 160);
        return $this->metaDescription;
    }

    public function getOgType()
    {
        return $this->ogType;
    }

    public function getUrls()
    {

        return $this->mediaUrl;
    }
    //        $seoTags = '';
//        foreach ($entity->getTags() as $tag) {
//            $seoTags .= $tag->getSlug() . ',';
//        }
//
//        $media = $entity->getMedia();
//        $provider = $this->container->get($media->getProviderName());
//        $url = $provider->generatePublicUrl($media, 'small');
////        $seoPage = $this->container->get('sonata.seo.page');
//        $seoPage->getTitle($entity->getTitle())
//                ->addHeadAttribute('prefix', 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#')
//                ->addMeta('name', 'keywords', $seoTags)
//                ->addMeta('name', 'description', implode(' ', array_slice(explode(' ', $entity->getContent()), 0, 10)))
//                ->addMeta('property', 'og:title', $entity->getTitle())
//                ->addMeta('property', 'og:type', 'article')
//                ->addMeta('property', 'og:image', $this->getRequest()->getUriForPath($url))
//                ->addMeta('property', 'og:url', $this->generateUrl('backend_article_show', array('id' => $id), true))
//                ->addMeta('property', 'og:description', implode(' ', array_slice(explode(' ', $entity->getContent()), 0, 10)))
//        ;
}
