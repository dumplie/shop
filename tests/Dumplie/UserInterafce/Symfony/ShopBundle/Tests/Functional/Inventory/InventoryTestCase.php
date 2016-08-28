<?php

declare (strict_types = 1);

namespace Dumplie\UserInterafce\Symfony\ShopBundle\Tests\Functional\Inventory;

use Dumplie\Inventory\Infrastructure\Doctrine\DBAL\Query\DbalInventoryQuery;
use Dumplie\Inventory\Tests\Doctrine\ORMInventoryContext;
use Dumplie\SharedKernel\Application\CommandBus;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;
use Dumplie\SharedKernel\Tests\Doctrine\DBALHelper;
use Dumplie\SharedKernel\Tests\Doctrine\ORMHelper;
use Dumplie\UserInterafce\Symfony\ShopBundle\Tests\Functional\WebTestCase;

class InventoryTestCase extends WebTestCase
{
    use ORMHelper;

    /**
     * @var ORMInventoryContext
     */
    protected $dbContext;

    public function setUp()
    {
        parent::setUp();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $commandBus = $this->getContainer()->get(Services::KERNEL_COMMAND_BUS);
        $this->dropSchema($em);
        $this->createSchema($em);
        $this->dbContext = new ORMInventoryContext(
            $em,
            new InMemoryEventLog(),
            new class($commandBus) implements CommandBusFactory
            {
                private $commandBus;

                public function __construct(CommandBus $commandBus)
                {
                    $this->commandBus = $commandBus;
                }

                public function create(array $handlers = [], array $commandExtension = []) : CommandBus
                {
                    return $this->commandBus;
                }
            }
        );
    }

    /**
     * @return DbalInventoryQuery
     */
    public function query() : DbalInventoryQuery
    {
        return new DbalInventoryQuery($this->getContainer()->get('database_connection'));

    }

    public function tearDown()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $this->dropSchema($em);
        parent::tearDown();
    }
}