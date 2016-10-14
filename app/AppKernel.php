<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            //SONATA MEDIA COMPONE.
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            // Pagination
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            //WYSWYG
            //new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            // FOS Users
            new FOS\UserBundle\FOSUserBundle(),
            // FPN TAGS
            //new Mykees\TagBundle\MykeesTagBundle(),
            new DCS\TagBundle\DCSTagBundle(),
            // Sonata SEO
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            // Doctrine extension for glug
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Kunstmaan\VotingBundle\KunstmaanVotingBundle(),
            new blackknight467\StarRatingBundle\StarRatingBundle(),
            new AppBundle\AppBundle(),
            new YallaWebsite\BackendBundle\YallaWebsiteBackendBundle(),
            new YallaWebsite\FrontendBundle\YallaWebsiteFrontendBundle(),
            new YallaWebsite\ModBundle\YallaWebsiteModBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    public function init()
    {
        date_default_timezone_set('Africa/Cairo');
        parent::init();
    }
}
