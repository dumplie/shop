<?php

namespace Dumplie\UserInterface\Symfony\ShopBundle;

use Dumplie\UserInterface\Symfony\ShopBundle\DependencyInjection\Compiler\DumplieTwigPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShopBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DumplieTwigPass());
    }
}
