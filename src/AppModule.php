<?php

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Kawanamiyuu\HtbFeed\Bookmark\BookmarkExtractor;
use Kawanamiyuu\HtbFeed\Bookmark\EntryListLoader;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponseBuilderFactory;
use Kawanamiyuu\HtbFeed\Http\RouterMiddleware;
use Kawanamiyuu\HtbFeed\Http\ServerRequestProvider;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiStreamEmitter;

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

        $this->bind(EmitterInterface::class)
            ->to(SapiStreamEmitter::class);

        $this->bind(ServerRequestInterface::class)
            ->toProvider(ServerRequestProvider::class)
            ->in(Scope::SINGLETON);

        // dependencies for HtbClient
        $this->bind(ClientInterface::class)->to(Client::class);
        $this->bind(EntryListLoader::class);
        $this->bind(BookmarkExtractor::class);

    }
}
