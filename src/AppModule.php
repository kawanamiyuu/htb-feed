<?php

namespace Kawanamiyuu\HtbFeed;

use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponseBuilderFactory;
use Kawanamiyuu\HtbFeed\Http\RouterMiddleware;
use Kawanamiyuu\HtbFeed\Http\ServerRequestProvider;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ResponderMiddleware::class);
        $this->bind(RouterMiddleware::class);

        $this->bind(ResponseBuilderFactory::class);

        $this->bind(ServerRequestInterface::class)
            ->toProvider(ServerRequestProvider::class)
            ->in(Scope::SINGLETON);

    }
}
