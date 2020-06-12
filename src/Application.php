<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\InjectorInterface;
use Relay\Relay;

class Application
{
    private InjectorInterface $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param string[] $middlewares
     */
    public function run(array $middlewares): void
    {
        $request = $this->injector->getInstance(ServerRequestInterface::class);

        $relay = new Relay($middlewares, function ($middleware) {
            return $this->injector->getInstance($middleware);
        });

        $relay->handle($request);
    }
}
