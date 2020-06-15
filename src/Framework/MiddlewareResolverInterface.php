<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareResolverInterface
{
    /**
     * @param string $middleware
     *
     * @return MiddlewareInterface|RequestHandlerInterface
     */
    public function __invoke(string $middleware);
}
