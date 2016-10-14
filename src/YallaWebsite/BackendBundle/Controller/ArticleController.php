<?php
/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */
namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Entity\Article;
use YallaWebsite\BackendBundle\Form\CreateArticleForm;
use YallaWebsite\BackendBundle\Form\EditArticleType;

class ArticleController extends Controller
{

    public function indexAction()
    {
            $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Article');
        $query = $entities->getLastAll();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 20
            );
        	} else {
            $pagination = NULL;
        }
        
        return $this->render('YallaWebsiteBackendBundle:Article:index.html.twig', array(
                'entities' => $pagination,
        ));
    }

    public function showAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Article')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }
        //$this->get('mk.tag_manager')->findTagRelation($entity);
//        $seoTags = '';
//        foreach ($entity->getTags() as $tag) {
//            $seoTags .= $tag->getSlug() . ',';
//        }
//
//        $media = $entity->getMedia();
//        $provider = $this->container->get($media->getProviderName());
//        $url = $provider->generatePublicUrl($media, 'small');
////        $seoPage = $this->container->get('sonata.seo.page');
//        $seoPage->setTitle($entity->getTitle())
//                ->addHeadAttribute('prefix', 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#')
//                ->addMeta('name', 'keywords', $seoTags)
//                ->addMeta('name', 'description', implode(' ', array_slice(explode(' ', $entity->getContent()), 0, 10)))
//                ->addMeta('property', 'og:title', $entity->getTitle())
//                ->addMeta('property', 'og:type', 'article')
//                ->addMeta('property', 'og:image', $this->getRequest()->getUriForPath($url))
//                ->addMeta('property', 'og:url', $this->generateUrl('backend_article_show', array('id' => $id), true))
//                ->addMeta('property', 'og:description', implode(' ', array_slice(explode(' ', $entity->getContent()), 0, 10)))
//        ;



        $this->prepareSEO($entity);
        return $this->render('YallaWebsiteBackendBundle:Article:show.html.twig', array(
                'entity' => $entity
        ));
    }

    public function newAction(Request $request)
    {
        $article = new Article();
        $manager = $this->getDoctrine()->getManager();
        $createForm = $this->createForm(new CreateArticleForm($manager), $article);
        if ($request->isMethod("POST")) {
            $createForm->handleRequest($request);
            if ($createForm->isValid()) {
                $article = $this->saveMedia($article);
                $article = $this->create($article, $request);
                return new RedirectResponse($this->generateUrl('backend_article_show', array('id' => $article->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Article:new.html.twig', array(
                        'form' => $createForm->createView(),
                        'error' => $createForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Article:new.html.twig', array(
                'form' => $createForm->createView()
        ));
    }

    public function editAction(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Articles Submited to Edit');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Article')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Article.');
        }
        $oldMedia = $entity->getMedia();
        $editForm = $this->createForm(new EditArticleType($this->getDoctrine()->getManager()), $entity);
        if ($this->getRequest()->isMethod('POST')) {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                if (is_null($entity->getMedia())) {
                    $entity->setMedia($oldMedia);
                } else {
                    $entity = $this->saveMedia($entity);
                    $this->deleteMedia($oldMedia);
                }
                $entity = $this->create($entity, $request);
                return new RedirectResponse($this->generateUrl('backend_article_show', array('id' => $entity->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Article:edit.html.twig', array(
                        'event' => $entity,
                        'form' => $editForm->createView(),
                        'error' => $editForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Article:edit.html.twig', array(
                'article' => $entity,
                'form' => $editForm->createView()
        ));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Article')->find($id);
        $media = $entity->getMedia();
        $this->deleteMedia($media);
        $this->deleteTags($entity);
        $em->remove($entity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('backend_article_index'));
    }

    private function deleteMedia($media)
    {
        if ($media != NULL) {
            $mediaManager = $this->container->get('sonata.media.manager.media');
            $mediaManager->delete($media);
        }
    }

    private function getCurentProfile()
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $curentProfileAuthor = $currentUser->getProfile();
        //dump($curentProfile);die();
        return $curentProfileAuthor;
    }

    private function create(Article $article, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article->setAuthor($this->getCurentProfile());
        $em->persist($article);
        $em->flush();

        return $article;
    }

    private function saveMedia($entity)
    {
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $entity->getMedia()->setContext('article');
        $mediaManager->save($entity->getMedia());
        return ($entity);
    }

    private function deleteTags($entity)
    {
        if ($entity != NULL) {
            $tags = $entity->getTags();
            foreach ($tags as $tag) {
                $entity->removeTag($tag);
            }
        }
    }

    private function prepareSEO($entity)
    {

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addHeadAttribute('prefix', 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#')
            ->setTitle('YallaNightLife - ' . $entity->getTitle())
            ->addMeta('name', 'description', $entity->getMetaDescription())
            ->addMeta('name', 'keywords', $entity->getMetaTags())
            ->addMeta('property', 'og:title', $entity->getTitle())
            ->addMeta('property', 'og:type', $entity->getOgType())
            ->addMeta('property', 'og:image', $this->getRequest()->getUriForPath($entity->getUrls($this->container->get('sonata.media.provider.image'))))
            ->addMeta('property', 'og:description', $entity->getMetaDescription())
            ->addMeta('property', 'og:url', $this->generateUrl('backend_article_show', array('id' => $entity->getId()), true))
            ->addMeta('property', 'twitter:description', $entity->getMetaDescription())
            ->addMeta('property', 'twitter:url', $this->generateUrl('backend_article_show', array('id' => $entity->getId()), true))
            ->addMeta('property', 'twitter:card', $entity->getMetaDescription())
            ->addMeta('property', 'twitter:title', $entity->getTitle())
            ->addMeta('property', 'twitter:title', $entity->getTitle())
        ;

        return $seoPage;
    }
}
