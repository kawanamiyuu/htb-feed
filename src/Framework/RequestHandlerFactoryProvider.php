<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;
use Relay\RelayBuilder as RequestHandlerFactory;

class RequestHandlerFactoryProvider implements ProviderInterface
{
    private InjectorInterface $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    public function get(): RequestHandlerFactory
    {
        return new RequestHandlerFactory(function ($middleware) {
            return $this->injector->getInstance($middleware);
        });
    }
}
