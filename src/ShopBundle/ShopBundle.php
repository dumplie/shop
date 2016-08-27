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
}
