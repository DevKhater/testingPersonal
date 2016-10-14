<?php


namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Form\CreatePhotographerForm;
use YallaWebsite\BackendBundle\Entity\Photographer;


/**
 * Description of PhotographerController
 *
 * @author Michel Khater
 */
class PhotographerController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $photographers = $em->getRepository("YallaWebsiteBackendBundle:Photographer")->findAll();
        return $this->render('YallaWebsiteBackendBundle:Photographer:index.html.twig', array(
                    'entities' => $photographers
        ));
    }

    public function newAction(Request $request)
    {
        $photographer = new Photographer();
        $em = $this->getDoctrine()->getManager();
        $createPhotographerForm = $this->createForm(new CreatePhotographerForm($em), $photographer);
        if ($this->getRequest()->isMethod('POST')) {
            $createPhotographerForm->handleRequest($request);
            if ($createPhotographerForm->isValid()) {
                $this->getDoctrine()->getManager()->persist($photographer);
                $this->getDoctrine()->getManager()->flush();
                return new RedirectResponse($this->generateUrl('backend_photographer_index'));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Photographer:new.html.twig', array(
                            'form' => $createPhotographerForm->createView(),
                            'form' => $createPhotographerForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Photographer:new.html.twig', array(
                    'form' => $createPhotographerForm->createView()
        ));
    }
    
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Photographers Submited to Edit');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Photographer')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find This Photographer.');
        }
        $editForm = $this->createForm(new CreatePhotographerForm($this->getDoctrine()->getManager()), $entity);
        if ($this->getRequest()->isMethod('POST')) {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
                return new RedirectResponse($this->generateUrl('backend_photographer_index'));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Photographer:edit.html.twig', array(
                        'entity' => $entity,
                        'form' => $editForm->createView(),
                        'error' => $editForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Photographer:edit.html.twig', array(
                'entity' => $entity,
                'form' => $editForm->createView()
        ));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('YallaWebsiteBackendBundle:Photographer')->find($id);
        $em->getRepository('ApplicationSonataMediaBundle:Gallery')->removePhotographerFromGalleries($entity, $em->getRepository('ApplicationSonataMediaBundle:Gallery')->findAll());
        //dump($galleries);exit;
        $em->remove($entity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('backend_photographer_index'));
    }
}
