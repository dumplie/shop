<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\ShopBundle\Form\Inventory;

use Dumplie\Inventory\Application\Query\InventoryQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sku', TextType::class, [
            'label' => 'inventory.storage.form.sku.label',
            'constraints' => [
                new NotBlank(),
                new Callback(['callback' => function($object, ExecutionContextInterface $context, $payload) use ($options) {
                    if (is_string($object) && $options['inventory_query']->skuExists($object)) {
                        $context->buildViolation('inventory.product.sku.not_unique')
                            ->atPath('sku')
                            ->addViolation();
                    }
                }])
            ]
        ]);
        $builder->add('price', MoneyType::class, [
            'label' => 'inventory.storage.form.price.label',
            'currency' => $options['currency']
        ]);
        $builder->add('available', CheckboxType::class, [
            'label' => 'inventory.storage.form.available.label'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('currency', null)
            ->setAllowedTypes('currency', ['string'])
            ->setRequired(['currency']);

        $resolver->setDefault('inventory_query', null)
            ->setAllowedTypes('inventory_query', [InventoryQuery::class])
            ->setRequired(['inventory_query']);
    }
}