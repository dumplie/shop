<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InventoryController extends Controller
{

    /**
     * @Route("/inventory", name="dumplie_inventory_dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('inventory/dashboard.html.twig', []);
    }

    /**
     * @Route("/inventory/storage", name="dumplie_inventory_storage")
     */
    public function storageAction()
    {
        return $this->render(':inventory/storage:index.html.twig', [
            'inventory' => $this->get('dumplie.inventory.query')->findAll(20),
            'totalCount' => $this->get('dumplie.inventory.query')->count(),
        ]);
    }
}
