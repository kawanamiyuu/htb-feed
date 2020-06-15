<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

interface ApplicationInterface
{
    /**
     * @param string[] $handlers
     */
    public function __invoke(array $handlers): void;
}
