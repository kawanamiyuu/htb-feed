<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

/**
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
class FrameworkModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(ServerRequestInterface::class)
            ->toProvider(ServerRequestProvider::class)->in(Scope::SINGLETON);

        $this->bind(ResponseFactoryInterface::class)
            ->to(ResponseFactory::class)->in(Scope::SINGLETON);

        $this->bind(RequestHandlerFactoryInterface::class)
            ->to(RequestHandlerFactory::class)->in(Scope::SINGLETON);

        $this->bind(ExceptionHandlerInterface::class)
            ->to(ExceptionHandler::class)->in(Scope::SINGLETON);

        $this->bind(ResponseEmitterInterface::class)
            ->toProvider(ResponseEmitterProvider::class)->in(Scope::SINGLETON);

        $this->bind(ApplicationInterface::class)
            ->to(Application::class)->in(Scope::SINGLETON);
    }
}
