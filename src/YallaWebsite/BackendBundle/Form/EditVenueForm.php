<?php namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditVenueForm extends AbstractType
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
                    'placeholder' => 'Venue Name')))
            ->add('location', 'location_information', array('data_class' => null))
            ->add('website', 'url', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Venue Website')))
            ->add('workingFrom', 'time', array(
                'placeholder' => array('hour' => 'Hour', 'minute' => 'Minute',),
                'widget' => 'choice',
                'html5' => false,
                'attr' => array('class'=>'form-control')
                )
                )
            
            ->add('workingTo', 'time', array(
                'placeholder' => array(
                    'hour' => 'Hour', 'minute' => 'Minute',
                )
            ))
            ->add('content', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Venue Description')))
            ->add('media', 'media_file', array(
                'required' => false
            ))
            ->add('tags', 'dcs_tag', array(
                'attr' => array('class' => 'form-control',
            )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\Venue',
        ));
    }

    public function getName()
    {
        return 'venue_edit';
    }
}
