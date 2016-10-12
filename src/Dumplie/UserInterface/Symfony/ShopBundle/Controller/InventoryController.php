<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\ShopBundle\Controller;

use Dumplie\Inventory\Application\Command\CreateProduct;
use Dumplie\Inventory\Application\Extension\Metadata;
use Dumplie\Inventory\Application\Services as InventoryServices;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\Metadata\Infrastructure\Symfony\Form\Type\MetadataType;
use Dumplie\UserInterface\Symfony\ShopBundle\Form\Inventory\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InventoryController extends Controller
{

    /**
     * @Route("/inventory", name="dumplie_inventory_dashboard")
     */
    public function dashboardAction() : Response
    {
        return $this->render('inventory/dashboard.html.twig', []);
    }

    /**
     * @Route("/inventory/storage", name="dumplie_inventory_storage")
     */
    public function storageAction() : Response
    {
        return $this->render(':inventory/storage:index.html.twig', [
            'products' => $this->get(InventoryServices::INVENTORY_APPLICATION_QUERY)->findAll(20),
            'totalCount' => $this->get(InventoryServices::INVENTORY_APPLICATION_QUERY)->count(),
        ]);
    }

    /**
     * @Route("/inventory/storage/new", name="dumplie_inventory_new")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request) : Response
    {
        $form = $this->createForm(ProductType::class, null, [
            'currency' => $this->getParameter('dumplie_currency'),
            'inventory_query' => $this->get(InventoryServices::INVENTORY_APPLICATION_QUERY)
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $command = new CreateProduct(
                $form->get('sku')->getData(),
                (int) $form->get('price')->getData(),
                $this->getParameter('dumplie_currency'),
                $form->get('available')->getData()
            );

            $this->get(Services::KERNEL_COMMAND_BUS)->handle($command);

            return $this->redirect($this->generateUrl('dumplie_inventory_metadata', ['sku' => $command->sku()]));
        }

        return $this->render(':inventory/storage:new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inventory/storage/metadata/{sku}", name="dumplie_inventory_metadata")
     * @Method({"GET", "POST"})
     */
    public function metadataAction(Request $request, string $sku) : Response
    {
        $mao = $this->get(Services::KERNEL_METADATA_ACCESS_REGISTRY)->getMAO(Metadata::TYPE_NAME);
        $metadata = $mao->findBy([Metadata::FIELD_SKU => $sku]);

        if (is_null($metadata)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MetadataType::class, $metadata, [
            'mao' => $mao,
            'type_options' => [
                Metadata::FIELD_SKU => ['disabled' => true, 'label' => 'inventory.storage.metadata.form.sku.label'],
                Metadata::FIELD_NAME => ['label' => 'inventory.storage.metadata.form.name.label'],
                Metadata::FIELD_VISIBLE => ['label' => 'inventory.storage.metadata.form.visible.label']
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mao->save($metadata);

            return $this->redirect($this->generateUrl('dumplie_inventory_storage'));
        }

        return $this->render(':inventory/storage:metadata.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
