<?php

namespace YallaWebsite\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use YallaWebsite\BackendBundle\Transformer\VenueLocationTransformer;

class VenueLocationType extends AbstractType
{

    private $manager;
    private $venues;
    private $list;

    public function __construct(\Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
        $trans = new VenueLocationTransformer($this->manager);
        $this->list = $trans->transform($this->venues);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('venue', 'choice', array(
                    'required' => false,
                    'choices' => $this->list,
                    'choices_as_values' => false,
                    'compound' => true,
                    'attr' => array('class' => 'dropdown form-control')
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'venue_location_information';
    }

}
