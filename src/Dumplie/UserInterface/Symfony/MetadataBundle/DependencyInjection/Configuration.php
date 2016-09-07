<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\MetadataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('metadata');

        $rootNode
            ->children()
                ->arrayNode('schema')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('builder_service_id')->defaultValue('dumplie.shared_kernel.metadata.schema.builder')->end() // doctrine
                    ->end()
                ->end()
                ->arrayNode('storage')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('connection')->defaultValue('database_connection')->end() // doctrine
                            ->arrayNode('doctrine')
                                ->canBeUnset()
                                ->children()
                                    ->scalarNode('connection')->isRequired()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}