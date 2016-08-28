<?php

declare (strict_types = 1);

namespace Dumplie\UserInterafce\Symfony\ShopBundle\Page\Inventory;

use Dumplie\UserInterafce\Symfony\ShopBundle\Page\BasePage;

class ProductListPage extends BasePage
{
    public function getUrl() : string
    {
        return '/inventory/storage';
    }
}