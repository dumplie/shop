<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\MetadataBundle\DependencyInjection;

use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TypeRegistry;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\DoctrineStorage;
use Dumplie\Metadata\Schema\Builder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class MetadataExtension extends Extension
{
    const STORAGE_SERVICE = 'dumplie.metadata.storage';

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $storage = current($config['storage']);
        $type = key($config['storage']);

        switch ($type) {
            case 'doctrine':
                $typeRegistryDefinition = new Definition(TypeRegistry::class,[]);
                $typeRegistryDefinition->setFactory([TypeRegistry::class, 'withDefaultTypes']);

                $container->setDefinition('dumplie.metadata.dbal.type_registry', $typeRegistryDefinition);

                $container->setDefinition(self::STORAGE_SERVICE, new Definition(
                    DoctrineStorage::class,
                    [
                        new Reference($storage['connection']),
                        new Reference('dumplie.metadata.dbal.type_registry')
                    ]
                ));
                break;
            default:
                throw new \RuntimeException(sprintf("Unknown storage type \"%s\"", $type));
                break;

        }

        $container->setParameter('dumplie.metadata.schema.builder.service', $config['schema']['builder_service_id']);

        if (!$container->hasDefinition($config['schema']['builder_service_id'])) {
            $container->setDefinition($config['schema']['builder_service_id'], new Definition(
                Builder::class
            ));
        }
    }
}