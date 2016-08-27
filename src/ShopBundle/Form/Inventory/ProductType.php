<?php

declare (strict_types = 1);

namespace ShopBundle\Form\Inventory;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sku', TextType::class, [
            'label' => 'SKU Code'
        ]);
        $builder->add('price', MoneyType::class, [
            'label' => 'Price',
            'currency' => $options['currency']
        ]);
        $builder->add('available', CheckboxType::class, [
            'label' => 'Available'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('currency', null)
            ->setAllowedTypes('currency', ['string'])
            ->setRequired(['currency']);
    }
}