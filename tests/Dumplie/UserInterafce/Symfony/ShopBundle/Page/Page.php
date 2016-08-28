<?php

declare (strict_types = 1);

namespace Dumplie\UserInterafce\Symfony\ShopBundle\Page;

interface Page
{
    /**
     * @param string $method
     * @return Page
     */
    public function open(string $method = 'GET') : Page;

    /**
     * @return string;
     */
    public function getUrl() : string;

    /**
     * @param string $url
     * @throws \RuntimeException
     * @return Page
     */
    public function shouldBeRedirectedFrom(string $url) : Page;

    /**
     * @param string $url
     * @throws \RuntimeException
     * @return Page
     */
    public function shouldBeRedirectedTo(string $url) : Page;
}