<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ExceptionHandlerInterface
{
    public function __invoke(Throwable $throwable): ResponseInterface;
}
