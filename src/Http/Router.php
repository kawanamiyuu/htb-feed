<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = Route::resolve($request);

        if ($route === null) {
            return $this->responseFactory->createResponse(404);
        }

        return $handler->handle($request->withAttribute(Route::class, $route));
    }
}
