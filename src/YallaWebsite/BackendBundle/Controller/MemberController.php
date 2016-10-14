<?php

/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Entity\Member;
use YallaWebsite\BackendBundle\Form\CreateMemberForm;
use YallaWebsite\BackendBundle\Form\EditMemberForm;

class MemberController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Member');
        $query = $entities->findAll();
        if ($query != NULL) {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $this->get('request')->query->get('page', 1), 20
            );
        } else {
            $pagination = NULL;
        }
        return $this->render('YallaWebsiteBackendBundle:Member:index.html.twig', array(
                    'entities' => count($query),
                    'pagination' => $pagination
        ));
    }

    public function showAction(Request $request)
    {
        $entity = $this->getMember($request);
        return $this->render('YallaWebsiteBackendBundle:Member:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    public function newAction(Request $request)
    {
        $member = new Member();
        $manager = $this->getDoctrine()->getManager();
        $createMemberForm = $this->createForm(new CreateMemberForm($manager), $member);
        if ($this->getRequest()->isMethod('POST')) {
            $createMemberForm->handleRequest($request);
            if ($createMemberForm->isValid()) {
                $BEManager = $this->container->get('backend_manager.manager');
                $BEManager->saveMedia($member, 'member');
                $member = $this->create($member);
                return new RedirectResponse($this->generateUrl('backend_member_show', array('id' => $member->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Member:new.html.twig', array(
                            'form' => $createMemberForm->createView(),
                            'error' => $createMemberForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Member:new.html.twig', array(
                    'form' => $createMemberForm->createView(),
        ));
    }

    public function editAction(Request $request)
    {
        $entity = $this->getMember($request);
        $oldMedia = $entity->getMedia();
        $editForm = $this->createForm(new EditMemberForm($this->getDoctrine()->getManager()), $entity);
        if ($this->getRequest()->isMethod('POST')) {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $BEManager = $this->container->get('backend_manager.manager');
                $BEManager->updateMedia($entity, $oldMedia, 'member');
                $entity = $this->create($entity);
                return new RedirectResponse($this->generateUrl('backend_member_show', array('id' => $entity->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Member:edit.html.twig', array(
                            'member' => $entity,
                            'form' => $editForm->createView(),
                            'error' => $editForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Member:edit.html.twig', array(
                    'member' => $entity,
                    'form' => $editForm->createView(),
        ));
    }

    public function deleteAction(Request $request)
    {
        $entity = $this->getMember($request);
        $BEManager = $this->container->get('backend_manager.manager');
        $BEManager->deleteMedia($entity->getMedia());
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($entity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('backend_member_index'));
    }

    private function create(Member $member)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($member);
        $em->flush();

        return $member;
    }

    private function getMember(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Member Submited to Edit');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Member')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Member.');
        }
        return $entity;
    }

}
