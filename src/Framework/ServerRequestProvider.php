<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\ProviderInterface;
use Laminas\Diactoros\ServerRequestFactory;

class ServerRequestProvider implements ProviderInterface
{
    public function get(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals();
    }
}
