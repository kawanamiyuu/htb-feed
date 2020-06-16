<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Server\RequestHandlerInterface;

interface RequestHandlerFactoryInterface
{
    /**
     * @param array<class-string> $handlers FQCNs of "PSR-15: HTTP Server Request Handlers" implementation
     *
     * @return RequestHandlerInterface
     */
    public function __invoke(array $handlers): RequestHandlerInterface;
}
