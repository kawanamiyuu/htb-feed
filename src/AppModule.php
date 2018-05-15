<?php

namespace Kawanamiyuu\HtbFeed;

use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Kawanamiyuu\HtbFeed\Http\RouterMiddleware;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ResponderMiddleware::class);
        $this->bind(RouterMiddleware::class);
    }
}
