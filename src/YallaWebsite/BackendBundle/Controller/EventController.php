<?php

/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Entity\Event;
use YallaWebsite\BackendBundle\Form\CreateEventForm;
use YallaWebsite\BackendBundle\Form\EditEventForm;
use YallaWebsite\BackendBundle\Transformer\VenueLocationTransformer;

class EventController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Event');
        $query = $entities->getLastAll();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 20
            );
        } else {
            $pagination = NULL;
        }
        return $this->render('YallaWebsiteBackendBundle:Event:index.html.twig', array(
                    'entities' => count($query),
                    'pagination' => $pagination
        ));
    }

    public function showAction(Request $request)
    {
        $entity = $this->getEvent($request);
        return $this->render('YallaWebsiteBackendBundle:Event:show.html.twig', array(
                    'entity' => $entity
        ));
    }

    public function newAction(Request $request)
    {
        $event = new Event();
        $manager = $this->getDoctrine()->getManager();
        $createEventForm = $this->createForm(new CreateEventForm($manager), $event, array('allow_extra_fields' => true));
        if ($this->getRequest()->isMethod('POST')) {
            $createEventForm->handleRequest($request);
            if ($createEventForm->isValid()) {
                $BEManager = $this->container->get('backend_manager.manager');
                $BEManager->saveMedia($event, 'event');
                $event = $this->create($event, 'save');
                return new RedirectResponse($this->generateUrl('backend_event_show', array('id' => $event->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Event:new.html.twig', array(
                            'form' => $createEventForm->createView(),
                            'error' => $createEventForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Event:new.html.twig', array(
                    'form' => $createEventForm->createView(),
                    'venues' => $this->getEventsAddress(),
        ));
    }

    public function editAction(Request $request)
    {
        $entity = $this->getEvent($request);
        $oldMedia = $entity->getMedia();
        $editForm = $this->createForm(new EditEventForm($this->getDoctrine()->getManager()), $entity, array('allow_extra_fields' => true));
        if ($this->getRequest()->isMethod('POST')) {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $BEManager = $this->container->get('backend_manager.manager');
                $BEManager->updateMedia($entity, $oldMedia, 'event');
                $entity = $this->create($entity);
                return new RedirectResponse($this->generateUrl('backend_event_show', array('id' => $entity->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Event:edit.html.twig', array(
                            'event' => $entity, 'form' => $editForm->createView(), 'list' => $this->getEventsAddress(), 'error' => $editForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Event:edit.html.twig', array(
                    'event' => $entity, 'form' => $editForm->createView(), 'list' => $this->getEventsAddress()
        ));
    }

    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $this->getEvent($request);
        $BEManager = $this->container->get('backend_manager.manager');
        $BEManager->deleteMedia($entity->getMedia());
        $BEManager->deleteTags($entity);
        $em->remove($entity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('backend_event_index'));
    }

    private function create(Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        return $event;
    }

    private function getEventsAddress()
    {
        $manager = $this->getDoctrine()->getManager();
        $trans = new VenueLocationTransformer($manager);
        $list = $trans->transform($manager->getRepository('YallaWebsiteBackendBundle:Event')->findAll());
        return $list;
    }

    private function getEvent(Request $request)
    {
        
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Event Submited to Edit');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Event')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Event.');
        }
        return $entity;
    }

}
