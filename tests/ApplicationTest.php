<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class ApplicationTest extends TestCase
{
    public function setUp(): void
    {
        // ray/di 内部で "Function ReflectionType::__toString() is deprecated" エラーが発生するのを抑止
        error_reporting(E_ALL & ~E_DEPRECATED);
    }

    public function tearDown(): void
    {
        error_reporting(E_ALL);
    }

    public function testCompile(): void
    {
        $injector = new Injector(new AppModule());
        $instance = $injector->getInstance(Application::class);

        $this->assertInstanceOf(Application::class, $instance);
    }
}
