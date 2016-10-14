<?php
/**
 * Description of ContactType
 *
 * @author Michel Khater
 */

namespace YallaWebsite\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'What\'s your name?',
                    'pattern'     => '.{2,}', //minlength,
                )
            ))
            ->add('email', 'email', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'So I can get back to you.'
                )
            ))
            ->add('subject', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'The subject of your message.',
                    'pattern'     => '.{3,}' //minlength
                )
            ))
            ->add('message', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'And your message to me...'
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'Name should not be blank.')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'Email should not be blank.')),
                new Email(array('message' => 'Invalid email address.'))
            ),
            'subject' => array(
                new NotBlank(array('message' => 'Subject should not be blank.')),
                new Length(array('min' => 3))
            ),
            'message' => array(
                new NotBlank(array('message' => 'Message should not be blank.')),
                new Length(array('min' => 5))
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}