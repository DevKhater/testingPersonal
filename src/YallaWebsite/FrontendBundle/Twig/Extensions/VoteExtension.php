<?php
/**
 * Description of YNLTwigSocialBar
 *
 * @author Michel Khater
 */
namespace YallaWebsite\FrontendBundle\Twig\Extensions;

class VoteExtension extends \Twig_Extension
{

    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'voteValue' => new \Twig_Function_Method($this, 'voteValue')
        );
    }

    public function voteValue($ImageReference)
    {
        return $this->container->get('kunstmaan_voting.helper.upvote')->countByReference($ImageReference);
    }

    public function getName()
    {
        return 'vote_extension';
    }
}
