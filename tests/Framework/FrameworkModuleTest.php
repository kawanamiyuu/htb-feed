<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class FrameworkModuleTest extends TestCase
{
    public function testCompile(): void
    {
        $injector = new Injector(new FrameworkModule([
            FakeMiddleware::class,
            FakeRequestHandler::class
        ]));

        $instance = $injector->getInstance(ApplicationInterface::class);

        $this->assertInstanceOf(ApplicationInterface::class, $instance);
    }
}
