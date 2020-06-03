<?php

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Kawanamiyuu\HtbFeed\Feed\FeedGeneratorInterface;
use Kawanamiyuu\HtbFeed\Http\ErrorHandlerMiddleware;
use Kawanamiyuu\HtbFeed\Http\FeedGeneratorProvider;
use Kawanamiyuu\HtbFeed\Http\FeedResponseBuilderMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Kawanamiyuu\HtbFeed\Http\ServerRequestProvider;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->bind(ResponderMiddleware::class);
        $this->bind(ErrorHandlerMiddleware::class);
        $this->bind(FeedResponseBuilderMiddleware::class);

        $this->bind(ServerRequestInterface::class)
            ->toProvider(ServerRequestProvider::class)
            ->in(Scope::SINGLETON);

        $this->bind(EmitterInterface::class)->to(SapiStreamEmitter::class);

        $this->bind(ClientInterface::class)->to(Client::class);

        $this->bind(FeedGeneratorInterface::class)->toProvider(FeedGeneratorProvider::class);
    }
}
