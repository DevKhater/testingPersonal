<?php

namespace YallaWebsite\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('facebook', 'url', array(
                'label' => 'Facebook','attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Facebook')))
                ->add('twitter', 'url', array(
                'label' => 'Twitter','attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Twitter')))
                ->add('soundcloud', 'url', array(
                'label' => 'SoundCloud','attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'SoundClound')))
                ->add('youtube', 'url', array(
                'label' => 'YouTube','attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Youtube')))
                ->add('googleplus', 'url', array(
                'label' => 'Google +','attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Google +')))
                ->add('instagram', 'url', array(
                'label' => 'Instagram','attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Instagram')))
                ;
    }
    
     public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\SocialMedia',
        ));
    }

    public function getName()
    {
        return 'social_media_information';
    }
}