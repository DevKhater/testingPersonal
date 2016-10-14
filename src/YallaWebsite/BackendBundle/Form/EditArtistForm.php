<?php namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditArtistForm extends AbstractType
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
                    'placeholder' => 'Artist Name')))
            ->add('biography', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Artist Description')))
            ->add('media', 'media_file', array(
                'required' => false
            ))
            ->add('sm', 'social_media_information', array(
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\Artist',
        ));
    }

    public function getName()
    {
        return 'artist_edit';
    }
}
