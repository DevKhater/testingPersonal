<?php

namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditArticleType extends AbstractType
{
     public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Article Name')))
                ->add('description', 'textarea', array(
                    'label' => false,
                   'required'    => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Short Description Max 150 character')))
                ->add('content', 'textarea', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'tinymce',
                        'row' => 20)))

                ->add('tags', 'dcs_tag', array(
                    'attr' => array('class' => 'form-control',
                )))
               ->add('media', 'media_file', array('required' =>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\Article',
        ));
    }

    public function getName()
    {
        return 'article';
    }

}
