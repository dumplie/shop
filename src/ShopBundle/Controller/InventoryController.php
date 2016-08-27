<?php

namespace ShopBundle\Controller;

use Dumplie\Inventory\Application\Command\CreateProduct;
use Dumplie\Inventory\Application\Services as InventoryServices;
use Dumplie\SharedKernel\Application\Services;
use ShopBundle\Form\Inventory\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

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
            'inventory' => $this->get(InventoryServices::INVENTORY_APPLICATION_QUERY)->findAll(20),
            'totalCount' => $this->get(InventoryServices::INVENTORY_APPLICATION_QUERY)->count(),
        ]);
    }

    /**
     * @Route("/inventory/storage/new", name="dumplie_inventory_new")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(ProductType::class, null, ['currency' => $this->getParameter('dumplie_currency')]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $command = new CreateProduct(
                $form->get('sku')->getData(),
                $form->get('price')->getData(),
                $this->getParameter('dumplie_currency'),
                $form->get('available')->getData()
            );

            $this->get(Services::KERNEL_COMMAND_BUS)->handle($command);

            return $this->redirect($this->generateUrl('dumplie_inventory_storage'));
        }

        return $this->render(':inventory/storage:add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
