<?php

namespace YallaWebsite\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use YallaWebsite\BackendBundle\Transformer\LocationInformationTransformer;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', 'text', array('attr' => array('class' => 'form-control',)))
            ->add('telephone', 'text', array('attr' => array('class' => 'form-control',)))
            ->addModelTransformer(new LocationInformationTransformer()) 
        ;
    }
    
     public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\LocationInformation',
        ));
    }

    public function getName()
    {
        return 'location_information';
    }
}