<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\ProviderInterface;
use Zend\Diactoros\ServerRequestFactory;

class ServerRequestProvider implements ProviderInterface
{
    /**
     * @return ServerRequestInterface
     */
    public function get(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals();
    }
}
