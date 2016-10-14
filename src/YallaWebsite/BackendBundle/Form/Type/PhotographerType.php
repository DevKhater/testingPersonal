<?php

namespace YallaWebsite\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotographerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('photographers', 'entity', array(
                    'class' => 'YallaWebsiteBackendBundle:Photographer',
                    'choice_label' => 'name',
                    'attr' => array('class' => 'form-control',)
                ));
    }
    
     public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function getName()
    {
        return 'photographer_type';
    }
}