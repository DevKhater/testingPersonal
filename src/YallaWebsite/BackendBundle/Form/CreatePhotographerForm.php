<?php

namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreatePhotographerForm extends AbstractType
{

    public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text', array('attr' => array('class' => 'form-control',)))
                ->add('website', 'url', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Photographer Website')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\Photographer',
        ));
    }

    public function getName()
    {
        return 'photographer_create';
    }

}
