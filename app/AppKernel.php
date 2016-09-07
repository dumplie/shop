<?php

use Dumplie\SharedKernel\Infrastructure\Symfony\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Dumplie\UserInterface\Symfony\MetadataBundle\DependencyInjection\MetadataExtension;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Dumplie\UserInterface\Symfony\MetadataBundle\MetadataBundle(),
            new Dumplie\UserInterface\Symfony\ShopBundle\ShopBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerDumplieExtensions()
    {
        $entityManagerId = 'doctrine.orm.default_entity_manager';
        $connectionId = 'database_connection';

        return [
            new \Dumplie\SharedKernel\Application\Extension\CoreExtension(MetadataExtension::STORAGE_SERVICE),
            new \Dumplie\SharedKernel\Infrastructure\Tactician\TacticianExtension(),
            new \Dumplie\SharedKernel\Infrastructure\Doctrine\DoctrineExtension($entityManagerId),

            new \Dumplie\Inventory\Application\Extension\CoreExtension(),
            new \Dumplie\Inventory\Infrastructure\Doctrine\DoctrineExtension($entityManagerId, $connectionId),
        ];
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
