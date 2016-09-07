<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\ShopBundle\Metadata;

use Dumplie\Metadata\Schema\Field\TextField;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\UserInterface\Symfony\MetadataBundle\Schema\Configuration as Config;

final class Configuration implements Config
{
    public function name() : string
    {
        return 'dumplie';
    }

    public function types() : array
    {
        return [
            new TypeSchema("inventory", [
                "SKU" => new TextField()
            ])
        ];
    }
}