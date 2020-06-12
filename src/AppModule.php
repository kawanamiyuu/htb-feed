<?php

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Kawanamiyuu\HtbFeed\Http\ServerRequestProvider;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(ServerRequestInterface::class)
            ->toProvider(ServerRequestProvider::class)
            ->in(Scope::SINGLETON);

        $this->bind(EmitterInterface::class)->to(SapiStreamEmitter::class);

        $this->bind(ClientInterface::class)->to(Client::class);
    }
}
