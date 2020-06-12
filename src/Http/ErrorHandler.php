<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    private ResponsePrototypeFactory $prototypeFactory;

    public function __construct(ResponsePrototypeFactory $prototypeFactory)
    {
        $this->prototypeFactory = $prototypeFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            error_log($e);

            return $this->prototypeFactory->newInstance()->withStatus(500);
        }
    }
}
