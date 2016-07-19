<?php

namespace ShopBundle;

use Doctrine\DBAL\Types\Type;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Type\Domain\CartItemsType;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Type\Domain\OrderItemsType;
use Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Type\Domain\OrderStateType;
use Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Type\Domain\PaymentStateType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShopBundle extends Bundle
{
    public function boot()
    {
        // Register Doctrine custom mapping types

        if (!Type::hasType(CartItemsType::NAME)) {
            Type::addType(CartItemsType::NAME, CartItemsType::class);
        }
        if (!Type::hasType(OrderItemsType::NAME)) {
            Type::addType(OrderItemsType::NAME, OrderItemsType::class);
        }
        if (!Type::hasType(OrderStateType::NAME)) {
            Type::addType(OrderStateType::NAME, OrderStateType::class);
        }
        if (!Type::hasType(PaymentStateType::NAME)) {
            Type::addType(PaymentStateType::NAME, PaymentStateType::class);
        }
    }
}
