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

        Type::addType(CartItemsType::NAME, CartItemsType::class);
        Type::addType(OrderItemsType::NAME, OrderItemsType::class);
        Type::addType(OrderStateType::NAME, OrderStateType::class);
        Type::addType(PaymentStateType::NAME, PaymentStateType::class);
    }
}
