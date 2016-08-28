<?php

declare (strict_types = 1);

namespace Dumplie\UserInterafce\Symfony\ShopBundle\Tests\Functional\Inventory;

use Dumplie\UserInterafce\Symfony\ShopBundle\Page\Inventory\AddProductPage;

class CreateProductTest extends InventoryTestCase
{
    function test_successful_product_create()
    {
        (new AddProductPage($this->client))
            ->open()
            ->fillForm("DUMPLIE_SKU", 100.00)
            ->pressSaveButton()
            ->shouldBeRedirectedTo('/inventory/storage');

        $this->assertTrue($this->query()->skuExists("DUMPLIE_SKU"));
    }
}