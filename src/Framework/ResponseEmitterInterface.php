<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ResponseInterface;

interface ResponseEmitterInterface
{
    public function __invoke(ResponseInterface $response): void;
}
