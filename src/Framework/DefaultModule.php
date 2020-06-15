<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Ray\Di\AbstractModule;
use Ray\Di\Scope;

/**
 * the module that provides default implementations
 */
class DefaultModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(MiddlewareResolverInterface::class)->to(MiddlewareResolver::class);

        $this->bind(ExceptionHandlerInterface::class)->to(ExceptionHandler::class);

        $this->bind(ResponseEmitterInterface::class)->toProvider(ResponseEmitterProvider::class);

        $this->bind(ApplicationInterface::class)->to(Application::class)->in(Scope::SINGLETON);
    }
}
