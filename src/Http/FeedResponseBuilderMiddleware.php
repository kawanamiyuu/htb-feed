<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FeedResponseBuilderMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponsePrototypeFactory
     */
    private $prototypeFactory;

    /**
     * @var ResponseBuilderFactory
     */
    private $builderFactory;

    /**
     * @param ResponsePrototypeFactory $prototypeFactory
     * @param ResponseBuilderFactory   $builderFactory
     */
    public function __construct(ResponsePrototypeFactory $prototypeFactory, ResponseBuilderFactory $builderFactory)
    {
        $this->prototypeFactory = $prototypeFactory;
        $this->builderFactory = $builderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $prototype = $this->prototypeFactory->newInstance();
        $builder = $this->builderFactory->newInstance(FeedResponseBuilder::class);

        return $builder($request, $prototype);
    }
}
