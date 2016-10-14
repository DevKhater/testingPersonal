<?php
/**
 * Description of YNLTwigSocialBar
 *
 * @author Michel Khater
 */
namespace YallaWebsite\BackendBundle\Twig\Extensions;

class ClassTwigExtension extends \Twig_Extension
{

    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */

    public function getFunctions()
    {
        return array(
            'class' => new \Twig_SimpleFunction('class', array($this, 'getClass'))
        );
    }

    public function getName()
    {
        return 'class_twig_extension';
    }

    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
