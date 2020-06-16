<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

interface ApplicationInterface
{
    /**
     * @param array<class-string> $handlers FQCNs of "PSR-15: HTTP Server Request Handlers" implementation
     */
    public function __invoke(array $handlers): void;
}
