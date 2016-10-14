<?php

/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */

namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{

    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $allUsers = $userManager->findUsers();
        return $this->render('YallaWebsiteBackendBundle:User:index.html.twig', array(
                    'entities' => $allUsers
        ));
    }

    public function newAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $createForm = $this->createNewForm($user);
        if ($request->isMethod("POST")) {
            $createForm->bind($request);
            if ($createForm->isValid()) {
                $user->setUsername($request->get('userName'));
                $user->setEmail($request->get('email'));
                $user->setPassword($request->get('plainPassword'));
                $user->setEnabled(1);
                $userManager->updateUser($user);
                return new RedirectResponse($this->generateUrl('backend_user_addpriv', array('id' => $user->getId())));
            } else {
                return $this->render('YallaWebsiteBackendBundle:User:new.html.twig', array(
                            'form' => $createForm->createView(),
                    'error' => $createForm->getErrors(),
                ));
            }
        }
        return $this->render('YallaWebsiteBackendBundle:User:new.html.twig', array(
                    'form' => $createForm->createView()
        ));
    }

    public function modifyAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $id = $request->get('id');
        $user = $userManager->findUserBy(array('id' => $id));
        $createForm = $this->createNewPrivilegForm();
        if ($request->isMethod("POST")) {
            $Priv = array_map(function($value) {
                return 'ROLE_ADMIN' . $value;
            }, $request->get('privileg'));
            $oldPriv = $user->getRoles();
            foreach ($oldPriv as $role) {
                if (!in_array($role, $Priv)) {
                    $user->removeRole($role);
                }
            }
            foreach ($Priv as $role) {
                $user->addRole($role);
            }
            $userManager->updateUser($user);
            return new RedirectResponse($this->generateUrl('backend_user_index', array('id' => $user->getId())));
        }
        return $this->render('YallaWebsiteBackendBundle:User:edit.html.twig', array(
                    'form' => $createForm->createView(),
                    'id' => $id
        ));
    }

    public function deleteAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $id = $request->get('id');
        $user = $userManager->findUserBy(array('id' => $id));
        $userManager->deleteUser($user);
        return new RedirectResponse($this->generateUrl('backend_user_index'));
    }

    private function createNewPrivilegForm()
    {
        $form = $this->get('form.factory')->createNamedBuilder(null, 'form', null, array('csrf_protection' => false))
                ->add('privileg', 'choice', array(
                    'choices' => array('Gallery' => '_G', 'Article' => '_A', 'Event' => '_E', 'Venue' => '_V'),
                    'multiple' => true,
                    'expanded' => false,
                    'choices_as_values' => true,
                    'attr' => array(
                        'class' => 'form-control')
                    ))
                ->getForm();
        return $form;
    }

    private function createNewForm($user)
    {
        $form = $this->get('form.factory')->createNamedBuilder(null, 'form', $user, array('csrf_protection' => false))
                ->add('userName', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Username')))
                ->add('email', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Email')))
                ->add('plainPassword', 'password', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',)
                ))
                ->getForm();
        return $form;
    }

}
