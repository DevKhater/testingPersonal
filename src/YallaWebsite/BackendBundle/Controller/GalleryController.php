<?php

/**
 * @author DevKhate<m.f.khater@gmail.com>
 *
 */

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Application\Sonata\MediaBundle\Entity\Gallery as BaseG;
use Application\Sonata\MediaBundle\Entity\GalleryHasMedia;
use YallaWebsite\BackendBundle\Form\GalleryUploadImagesType;
use YallaWebsite\BackendBundle\Form\GalleryAddTagsForm;
use Symfony\Component\HttpFoundation\JsonResponse;

class GalleryController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query= $em->getRepository("ApplicationSonataMediaBundle:Gallery")->findBy([], ['createdAt' => 'DESC']);
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 20
            );
        } else {
            $pagination = NULL;
        }
        
        return $this->render('YallaWebsiteBackendBundle:Gallery:index.html.twig', array(
                    'counting' => count($query) ,'pagination' => $pagination
        ));
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = new BaseG();
        $gallery->setDefaultFormat("gallery_small");
        $gallery->setContext("gallery");
        $gallery->setEnabled(1);
        $createGalleryForm = $this->createForm(new GalleryAddTagsForm($em), $gallery);
        if ($this->getRequest()->isMethod('POST')) {
            $createGalleryForm->handleRequest($request);
            if ($createGalleryForm->isValid()) {
                $this->getDoctrine()->getManager()->persist($gallery);
                $this->getDoctrine()->getManager()->flush();
                return new RedirectResponse($this->generateUrl('backend_gallery_show', array('id' => $gallery->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Gallery:new.html.twig', array(
                            'form' => $createGalleryForm->createView(),
                            'form' => $createGalleryForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Gallery:new.html.twig', array(
                    'form' => $createGalleryForm->createView()
        ));
    }

    public function showAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('ApplicationSonataMediaBundle:Gallery')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }
        $galleryMedia = $this->getDoctrine()->getManager()->getRepository("ApplicationSonataMediaBundle:GalleryHasMedia");
        $query = $galleryMedia->findByGallery($id);
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $request->query->getInt('page', 1), 20);
        } else {
            $pagination = NULL;
        }
        return $this->render('YallaWebsiteBackendBundle:Gallery:show.html.twig', array(
                    'entity' => $entity,
                    'pagination' => $pagination
        ));
    }

    public function addImagesAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository("ApplicationSonataMediaBundle:Gallery")->find($id);
        $addImageForm = $this->createForm(new GalleryUploadImagesType($em), $gallery);
        if ($request->isMethod('POST')) {
            $addImageForm->bind($request);
            if ($addImageForm->isValid()) {
                $this->addImagesToGallery($gallery, $addImageForm->get('media')->getData());
                return new RedirectResponse($this->generateUrl('backend_gallery_show', array('id' => $id)));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Gallery:add.html.twig', array(
                            'data' => $id,
                            'addForm' => $addImageForm->createView(),
                            'error' => $addImageForm->getErrors(),));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Gallery:add.html.twig', array(
                    'data' => $id,
                    'addForm' => $addImageForm->createView(),
        ));
    }

    public function editAction(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Gallery Submited to Edit');
        }
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('ApplicationSonataMediaBundle:Gallery')->find($id);
        $createGalleryForm = $this->createForm(new GalleryAddTagsForm($em), $gallery);
        if ($this->getRequest()->isMethod('POST')) {
            if (($request->get('gallery_tags')['tags']) == "") {
                $request->request->set('tags', 'Gallery');
            }
            $createGalleryForm->handleRequest($request);
            if ($createGalleryForm->isValid()) {
                $this->getDoctrine()->getManager()->persist($gallery);
                $this->getDoctrine()->getManager()->flush();
                return new RedirectResponse($this->generateUrl('backend_gallery_show', array('id' => $gallery->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Gallery:edit.html.twig', array(
                            'form' => $createGalleryForm->createView(),
                            'form' => $createGalleryForm->getErrors(),
                            'entity' => $gallery));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Gallery:edit.html.twig', array(
                    'form' => $createGalleryForm->createView(),
                    'entity' => $gallery));
    }

    public function setAjaxGalleryPreviewAction(Request $request)
    {
        $data1 = $request->get('id');
        $gID = $request->get('g');

        $em = $this->getDoctrine()->getManager();
        $G = $em->getRepository("ApplicationSonataMediaBundle:Gallery")->find($gID);
        $M = $em->getRepository('ApplicationSonataMediaBundle:Media')->find($data1);
        $G->setMedia($M);
        $em->persist($G);
        $em->flush();
        return new JsonResponse(array('code' => 100, 'data' => "Preview Image Changed."));
    }

    public function delAjaxGalleryTagAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data1 = $request->get('id');
        $gID = $request->get('g');

        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository("YallaWebsiteBackendBundle:Tag")->find($data1);
        $G = $em->getRepository("ApplicationSonataMediaBundle:Gallery")->find($gID);
        $G->removeTag($tag);
        $em->persist($G);
        $em->flush();
        return new JsonResponse(array('code' => 100));
    }

    public function setGalleryPreviewAction(Request $request)
    {
        $gallery_id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('ApplicationSonataMediaBundle:Gallery')->find($gallery_id);
        if (!$gallery) {
            throw $this->createNotFoundException('Unable to find Gallery entity.');
        }
        $query = $em->getRepository("ApplicationSonataMediaBundle:GalleryHasMedia")->findBy(array('gallery' => $gallery_id));
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 50
            );
        } else {
            $pagination = NULL;
        }

        return $this->render('YallaWebsiteBackendBundle:Gallery:setpreview.html.twig', array(
                    'entity' => $gallery,
                    'pagination' => $pagination,
        ));
    }

    public function deleteImageAction(Request $request)
    {
        $id = $request->get('id');
        $gid = $request->get('galleryid');
        $em = $this->getDoctrine()->getManager();
        $G = $em->getRepository("ApplicationSonataMediaBundle:Gallery")->find($gid);
        if ($em->getRepository("ApplicationSonataMediaBundle:GalleryHasMedia")->find($id)->getMedia()->getId() == $G->getMedia()->getId()) {
            $this->addFlash('error', "Can't Delete Image, It's The Gallery Preview for the Album");
            return new RedirectResponse($this->generateUrl('backend_gallery_show', array('id' => $gid)));
        } else {
            $medias = $em->getRepository("ApplicationSonataMediaBundle:GalleryHasMedia")->find($id);
            $this->deleteImages($medias);
            return new RedirectResponse($this->generateUrl('backend_gallery_show', array('id' => $request->get('galleryid'))));
        }
    }

    public function deleteGalleryAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository("ApplicationSonataMediaBundle:Gallery")->find($id);
        $medias = $em->getRepository("ApplicationSonataMediaBundle:GalleryHasMedia")->findBy(array('gallery' => $id));
        foreach ($medias as $media) {
            $this->deleteImages($media);
        }
        $this->deleteTags($gallery);
        $em->remove($gallery);
        $em->flush();
        return new RedirectResponse($this->generateUrl('backend_gallery_index'));
    }

    private function deleteImages($medias)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $medias->getMedia();
        $em->remove($medias);
        $em->flush();
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $mediaManager->delete($media);
    }

    private function addImagesToGallery($gallery, $files)
    {
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($files as $media) {

            $media->setContext('gallery');
            $em->persist($media);
            $ghm = new GalleryHasMedia();
            $ghm->setGallery($gallery);
            $ghm->setMedia($media);
            $ghm->setEnabled(1);
            $em->persist($ghm);
            $gallery->addGalleryHasMedias($ghm);
            $em->persist($gallery);
            $em->flush();
        }
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

    private function checkEmptyTags($entity)
    {
        if (count($entity->getTags()) == 0) {
            $tagManager = $this->container->get('dcs_tag.manager');
            $entity->addTag($tagManager->add('Gallery'));
        }
    }

}
