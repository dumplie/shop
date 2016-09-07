<?php

declare (strict_types = 1);

namespace Dumplie\UserInterafce\Symfony\ShopBundle\Page\Inventory;

use Dumplie\UserInterafce\Symfony\ShopBundle\Page\BasePage;
use Dumplie\UserInterafce\Symfony\ShopBundle\Page\Page;

class AddProductPage extends BasePage
{
    private $form;

    public function getUrl() : string
    {
        return '/inventory/storage/new';
    }

    public function fillForm(string $sku, float $price) : Page
    {
        $this->form = $this->getCrawler()->filter("form[name=\"product\"]")->form([
            'product[sku]' => $sku,
            'product[price]' => $price,
            'product[available]' => true
        ], 'POST');

        return $this;
    }

    /**
     * @return $this|ProductListPage
     */
    public function pressSaveButton() : ProductListPage
    {
        $this->client->submit($this->form);

        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            $this->client->followRedirect();
            return new ProductListPage($this->client, $this);
        }

        throw new \RuntimeException(sprintf("Unexpected status code: %d", $status));
    }
}