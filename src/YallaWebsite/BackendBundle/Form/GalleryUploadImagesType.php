<?php

namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GalleryUploadImagesType extends AbstractType
{
     public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
               ->add('media', 'multi_media_files', array( 'mapped' => false, 'label' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\MediaBundle\Entity\Gallery',
//            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'gallery_upload';
    }

}
