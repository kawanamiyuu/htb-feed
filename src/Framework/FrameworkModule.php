<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class FrameworkModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(ServerRequestInterface::class)
            ->toProvider(ServerRequestProvider::class)->in(Scope::SINGLETON);

        $this->bind(ResponseFactoryInterface::class)->to(ResponseFactory::class);

        $this->install(new DefaultModule());
    }
}
