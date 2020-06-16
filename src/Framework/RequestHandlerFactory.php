<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Server\RequestHandlerInterface;
use Ray\Di\InjectorInterface;
use Relay\Relay;

class RequestHandlerFactory implements RequestHandlerFactoryInterface
{
    private InjectorInterface $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(array $handlers): RequestHandlerInterface
    {
        return new Relay($handlers, function ($middleware) {
            return $this->injector->getInstance($middleware);
        });
    }
}
