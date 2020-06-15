<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ray\Di\InjectorInterface;

class MiddlewareResolver implements MiddlewareResolverInterface
{
    private InjectorInterface $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param string $middleware
     *
     * @return MiddlewareInterface|RequestHandlerInterface
     */
    public function __invoke(string $middleware)
    {
        return $this->injector->getInstance($middleware);
    }
}
