<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;

interface ApplicationInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param array<class-string>    $handlers FQCNs of "PSR-15: HTTP Server Request Handlers" implementation
     */
    public function __invoke(ServerRequestInterface $request, array $handlers): void;
}
