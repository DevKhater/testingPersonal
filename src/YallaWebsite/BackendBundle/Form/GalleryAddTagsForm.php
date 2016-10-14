<?php

namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GalleryAddTagsForm extends AbstractType
{

    public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Gallery Name')))
                ->add('date', 'date', array(
                    'placeholder' => array(
                        'year' => 'Year', 'month' => 'Month', 'day' => 'Day'
                    )
                ))
                ->add('description', 'textarea', array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Gallery Description')))
                ->add('photographers', 'entity', array(
                    'class' => 'YallaWebsiteBackendBundle:Photographer',
                    'choice_label' => 'name',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'attr' => array('class' => 'form-control',)
                ))
                ->add('tags', 'dcs_tag', array(
                    'attr' => array('class' => 'form-control',
            )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\MediaBundle\Entity\Gallery',
        ));
    }

    public function getName()
    {
        return 'gallery_tags';
    }

}
