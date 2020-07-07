<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;

interface ApplicationInterface
{
    /**
     * @param ServerRequestInterface $request
     */
    public function __invoke(ServerRequestInterface $request): void;
}
