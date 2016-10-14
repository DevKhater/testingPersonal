<?php

namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditProfileForm extends AbstractType
{
     public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('displayName', 'text', array(
                    'label' => false,
                    'required' =>false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Display Name')))
                ->add('shortBio', 'textarea', array(
                    'label' => false,
                    'required' =>false,
                    'attr' => array('class' => 'form-control')
                    ))
                ->add('media', 'media_file', array('required' =>false,))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\UserProfile',
        ));
    }

    public function getName()
    {
        return 'create_profile';
    }

}
