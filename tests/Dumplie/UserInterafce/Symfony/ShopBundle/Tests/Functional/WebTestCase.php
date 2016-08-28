<?php

declare (strict_types = 1);

namespace Dumplie\UserInterafce\Symfony\ShopBundle\Tests\Functional;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;

class WebTestCase extends BaseTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function getContainer()
    {
        return $this->client->getContainer();
    }
}