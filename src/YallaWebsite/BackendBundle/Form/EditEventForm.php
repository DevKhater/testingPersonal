<?php namespace YallaWebsite\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditEventForm extends AbstractType
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
                    'placeholder' => 'Event Name')))
            ->add('startDate', 'datetime', array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'date_picker form-control',)))
            ->add('endDate', 'datetime', array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'date_picker form-control',)))
            ->add('isVenue', 'checkbox', array(
                'label' => 'Is it A Venue?',
                'required' => false,
            ))
            ->add('location', 'location_information', array(
                'required' => false,
                'data_class' => null
            ))
            ->add('venue', 'entity', array(
                'class' => 'YallaWebsiteBackendBundle:Venue',
                'choice_label' => 'title',
                'attr' => array('class' => 'form-control',)
            ))
            ->add('content', 'textarea', array(
                'label' => false,
                'attr' => array('placeholder' => 'Venue Description', 'class' => 'dropdown form-control', 'rows' => 10)))
            ->add('tags', 'dcs_tag', array(
                'attr' => array('class' => 'form-control',
            )))
            ->add('similarArtist', 'entity', array(
                'required' => false,
                'class' => 'YallaWebsiteBackendBundle:Artist',
                'choice_label' => 'title',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'attr' => array('class' => 'form-control',)
            ))
            ->add('media', 'media_file', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YallaWebsite\BackendBundle\Entity\Event',
        ));
    }

    public function getName()
    {
        return 'event_edit';
    }
}
