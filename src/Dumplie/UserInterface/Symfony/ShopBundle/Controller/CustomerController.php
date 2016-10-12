<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\ShopBundle\Controller;

use Dumplie\Inventory\Application\Services;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @Route("/", name="dumplie_customer_homepage")
     */
    public function homepageAction() : Response
    {
        $inventory = $this->get(Services::INVENTORY_APPLICATION_QUERY)->findAll(10, 0);

        return $this->render(':customer/homepage:index.html.twig', [
            'inventory' => $inventory
        ]);
    }

    /**
     * @Route("/product/{sku}", name="dumplie_customer_product_details")
     */
    public function detailsAction(string $sku) : Response
    {
        $product = $this->get(Services::INVENTORY_APPLICATION_QUERY)->getBySku($sku);

        return $this->render(':customer/product:details.html.twig', [
            'product' => $product
        ]);
    }
}
