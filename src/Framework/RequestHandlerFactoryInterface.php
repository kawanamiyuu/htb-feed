<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Server\RequestHandlerInterface;

interface RequestHandlerFactoryInterface
{
    /**
     * @param string[] $handlers
     *
     * @return RequestHandlerInterface
     */
    public function __invoke(array $handlers): RequestHandlerInterface;
}
