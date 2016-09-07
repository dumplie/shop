<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\ShopBundle\DependencyInjection\Compiler;

use Dumplie\Inventory\UserInterface\Views as InventoryViews;
use Dumplie\SharedKernel\Application\Services;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DumplieTwigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $contextMap = $container->getDefinition(Services::KERNEL_RENDERING_CONTEXT_MAP);
        $dumplieTwigLoader = $container->getDefinition('dumplie.twig.loader.file_loader');

        $dumplieTwigLoader->addMethodCall('addContextPath', [
            '%kernel.root_dir%/Resources/views/dumplie/context/inventory',
            'dumplie_symfony_inventory'
        ]);
        $contextMap->addMethodCall('extendContext', [InventoryViews::CONTEXT, 'dumplie_symfony_inventory']);
    }
}