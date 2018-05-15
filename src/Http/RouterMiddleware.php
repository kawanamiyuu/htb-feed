<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

class RouterMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseBuilderFactory
     */
    private $factory;

    /**
     * @param ResponseBuilderFactory $factory
     */
    public function __construct(ResponseBuilderFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $responseBuilderClass = Route::matches($request);
        $responseBuilder = $this->factory->newInstance($responseBuilderClass);

        return $responseBuilder($request, new Response);
    }
}
