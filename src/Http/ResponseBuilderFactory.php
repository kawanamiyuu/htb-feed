<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Ray\Di\InjectorInterface;

class ResponseBuilderFactory
{
    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @param InjectorInterface $injector
     */
    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param string $class
     *
     * @return ResponseBuilderInterface
     */
    public function newInstance(string $class): ResponseBuilderInterface
    {
        return $this->injector->getInstance($class);
    }
}
