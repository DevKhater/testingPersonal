<?php

/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Entity\Venue;
use YallaWebsite\BackendBundle\Form\CreateVenueForm;
use YallaWebsite\BackendBundle\Form\EditVenueForm;

class VenueController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Venue');
        $query = $entities->getLastAll();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 20
            );
        } else {
            $pagination = NULL;
        }
        return $this->render('YallaWebsiteBackendBundle:Venue:index.html.twig', array(
                    'entities' => count($query),
                    'pagination' => $pagination
        ));
    }

    public function showAction(Request $request)
    {
        $entity = $this->getVenue($request);
        return $this->render('YallaWebsiteBackendBundle:Venue:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    public function newAction(Request $request)
    {
        $venue = new Venue();
        $manager = $this->getDoctrine()->getManager();
        $createVenueForm = $this->createForm(new CreateVenueForm($manager), $venue);
        if ($this->getRequest()->isMethod('POST')) {
            $createVenueForm->handleRequest($request);
            if ($createVenueForm->isValid()) {
                $BEManager = $this->container->get('backend_manager.manager');
                $BEManager->saveMedia($venue, 'venue');
                $venue = $this->create($venue);
                return new RedirectResponse($this->generateUrl('backend_venue_show', array('id' => $venue->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Venue:new.html.twig', array(
                            'form' => $createVenueForm->createView(),
                            'error' => $createVenueForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Venue:new.html.twig', array(
                    'form' => $createVenueForm->createView(),
        ));
    }

    public function editAction(Request $request)
    {
        $entity = $this->getVenue($request);
        $oldMedia = $entity->getMedia();
        $editForm = $this->createForm(new EditVenueForm($this->getDoctrine()->getManager()), $entity);
        if ($this->getRequest()->isMethod('POST')) {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $BEManager = $this->container->get('backend_manager.manager');
                $BEManager->updateMedia($entity, $oldMedia, 'venue');
                $entity = $this->create($entity);
                return new RedirectResponse($this->generateUrl('backend_venue_show', array('id' => $entity->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Venue:edit.html.twig', array(
                            'venue' => $entity,
                            'form' => $editForm->createView(),
                            'error' => $editForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Venue:edit.html.twig', array(
                    'venue' => $entity,
                    'form' => $editForm->createView(),
        ));
    }

    public function deleteAction(Request $request)
    {
        $entity = $this->getVenue($request);
        $BEManager = $this->container->get('backend_manager.manager');
        $BEManager->deleteMedia($entity->getMedia());
        $BEManager->deleteTags($entity);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($entity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('backend_venue_index'));
    }

    private function create(Venue $venue)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($venue);
        $em->flush();

        return $venue;
    }

    private function getVenue(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Venue Submited to Edit');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Venue')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Venue.');
        }
        return $entity;
    }

}
