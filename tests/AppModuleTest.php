<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

use PHPUnit\Framework\TestCase;
use Ray\Compiler\DiCompiler;
use Ray\Di\Exception\Unbound;

class AppModuleTest extends TestCase
{
    public function testCompile(): void
    {
        $compiler = new DiCompiler(new AppModule(), dirname(__DIR__) . '/build/tests');

        try {
            $compiler->compile();
            $compiler->dumpGraph();
        } catch (Unbound $e) {
            $this->fail((string) $e);
        }
    }
}
