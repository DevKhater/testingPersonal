<?php
namespace YallaWebsite\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use YallaWebsite\BackendBundle\Transformer\MultiMediaFileTransformer;
use Sonata\AdminBundle\Validator\InlineValidator;

class multiMediaType extends AbstractType
{
//     public function __construct(\Doctrine\ORM\EntityManager $manager)
//    {
//        $this->manager = $manager;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('media', 'file', array(
                'multiple' => true, 
                'data_class' => null,
        ))
                ->addModelTransformer(new MultiMediaFileTransformer())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    public function getName()
    {
        return 'multi_media_files';
    }

}
