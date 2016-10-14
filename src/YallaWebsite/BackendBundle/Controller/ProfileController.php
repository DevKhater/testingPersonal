<?php

/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Entity\User;
use YallaWebsite\BackendBundle\Entity\UserProfile;
use Application\Sonata\MediaBundle\Entity\Media;

class ProfileController extends Controller
{

    public function indexAction(Request $request)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $pro = $currentUser->getProfile();
        if (!$pro) {
            return new \Symfony\Component\HttpFoundation\RedirectResponse ($this->generateUrl('backend_profile_new'));
        }
        return $this->render('YallaWebsiteBackendBundle:Profile:index.html.twig', array(
                'entities' => $pro
                ));
        }
        
    public function createAction(Request $request)
            {
        $manager = $this->getDoctrine()->getManager();
        $entity = new UserProfile();
        $createForm = $this->createForm(new \YallaWebsite\BackendBundle\Form\CreateProfileForm ($manager), $entity);
        if ($request->isMethod("POST")) {
            $createForm->handleRequest($request);
            if ($createForm->isValid()) {
                $this->createProfile($entity);
                return new RedirectResponse($this->generateUrl('backend_profile_index'));
            } else { 
                return $this->render('YallaWebsiteBackendBundle:Profile:new.html.twig', array(
                    'form' => $createForm->createView(),
                    'error' => $createForm->getErrors()));
                }
                }
        return $this->render('YallaWebsiteBackendBundle:Profile:new.html.twig', array(
                    'form' => $createForm->createView(),
        ));
    }
    
     private function createProfile (UserProfile $profile)
    {
        $em = $this->getDoctrine()->getManager();        
        $profile = $this->saveMedia($profile);
        $em->persist($profile);
        $em->flush();
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $currentUser->setProfile($profile);
        $em->persist($currentUser);
        $em->flush();
        
    }
    
    private function saveMedia($entity)
    {
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $entity->getMedia()->setContext('profile');
        $mediaManager->save($entity->getMedia());
        
        return $entity;
    }

    
    public function editAction(Request $request)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $entity = $currentUser->getProfile();
        if (!$entity) {
            return new \Symfony\Component\HttpFoundation\RedirectResponse ($this->generateUrl('backend_profile_new'));
        }
        $manager = $this->getDoctrine()->getManager();
        $oldMedia = $entity->getMedia();
        $editForm = $this->createForm(new \YallaWebsite\BackendBundle\Form\EditProfileForm ($manager), $entity);
        //dump($request);die();
        if ($this->getRequest()->isMethod('POST')) {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                //dump($entity); dump($editForm);die();
                if (is_null($entity->getMedia())) {
                    $entity->setMedia($oldMedia);
                } else {
                    $entity = $this->saveMedia($entity);
                    $this->deleteMedia($oldMedia);
                }
                $this->createProfile($entity);
                return new RedirectResponse($this->generateUrl('backend_profile_index'));
            } else {
                return $this->render('YallaWebsiteBackendBundle:Profile:edit.html.twig', array(
                            'entities' => $entity,
                            'form' => $editForm->createView(),
                            'error' => $editForm->getErrors()));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:Profile:edit.html.twig', array(
                    'entities' => $entity,
            'form' => $editForm->createView(),
        ));
    }
  

    private function getCurentProfile()
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        //$em = $this->getDoctrine()->getManager();
        //$curentProfile = $em->getRepository('YallaWebsiteBackendBundle:User')->findBy(array('profile' => $currentUser));
        $curentProfile = $currentUser->getProfile();
        //dump($curentProfile);die();
        return $curentProfile;
    }
    private function createNewForm($profile)
    {
        $form = $this->get('form.factory')->createNamedBuilder(null, 'form', $profile, array('csrf_protection' => false))
                ->add('displayName', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Username')))
                ->add('shortBio', 'textarea', array(
                'label' => false,
                    'attr' => array('class' => 'form-control')
                    ))
                ->add('media', 'file', array(
                    'data_class' => null,
                    'required' => false,
                    'attr' => array(
            )))
                ->getForm();
        return $form;
    }

    private function updateProfileMedia($entity, $files)
    {
        $mediaManager = $this->container->get('sonata.media.manager.media');
        foreach ($files as $uploadedFile) {
            $newmedia = new Media();
            $newmedia->setBinaryContent($uploadedFile);
            $newmedia->setContext('profile');
            $newmedia->setProviderName('sonata.media.provider.image');
            $mediaManager->save($newmedia);
            $entity->setMedia($newmedia);
        }
        return ($entity);
    }

    private function deleteMedia($media)
    {
         if ($media != NULL) {
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $mediaManager->delete($media);
    }
    }

    private function save(UserProfile $profile, Request $request)
    {
//        $currentUser = $this->get('security.token_storage')->getToken()->getUser()->getProfile()->getId();
        $em = $this->getDoctrine()->getManager();
//        $profile = $em->getRepository("YallaWebsiteBackendBundle:UserProfile")->find($currentUser);
        $profile->setDisplayName($request->get('displayName'));
        $profile->setShortBio($request->get('shortBio'));
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $profile->setProfile($currentUser);
        $em->persist($profile);
        $em->flush();
        dump($profile);
        return $profile;
    }
}
