<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Feed\Configuration;
use Kawanamiyuu\HtbFeed\Feed\FeedGeneratorInterface;
use Psr\Http\Message\ServerRequestInterface;

class FeedGeneratorResolver
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return FeedGeneratorInterface
     */
    public function __invoke(ServerRequestInterface $request): FeedGeneratorInterface
    {
        $config = new Configuration(
            Route::HTML()->getUrl($request),
            Route::ATOM()->getUrl($request),
            Route::RSS()->getUrl($request)
        );

        $generator = Route::resolve($request)->feedType()->generator();

        return new $generator($config);
    }
}
