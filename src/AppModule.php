<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use K9u\Framework\FrameworkModule;
use Kawanamiyuu\HtbFeed\Http\RequestHandler;
use Kawanamiyuu\HtbFeed\Http\Router;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $middlewares = [
            Router::class,
            RequestHandler::class
        ];

        $this->install(new FrameworkModule($middlewares));

        $this->bind(ClientInterface::class)
            ->to(Client::class)->in(Scope::SINGLETON);
    }
}
