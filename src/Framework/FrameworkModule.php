<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

/**
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
class FrameworkModule extends AbstractModule
{
    /**
     * @var array<class-string>
     */
    private array $middlewares;

    /**
     * @param array<class-string> $middlewares
     * @param AbstractModule|null $module
     */
    public function __construct(array $middlewares, AbstractModule $module = null)
    {
        $this->middlewares = $middlewares;
        parent::__construct($module);
    }

    protected function configure(): void
    {
        foreach ($this->middlewares as $middleware) {
            $this->bind($middleware)->in(Scope::SINGLETON);
        }

        $this->bind(MiddlewareContainer::class)
            ->toInstance(new MiddlewareContainer($this->middlewares));

        $this->bind(RequestHandlerInterface::class)
            ->toProvider(RequestHandlerProvider::class)->in(Scope::SINGLETON);

        $this->bind(ResponseFactoryInterface::class)
            ->to(ResponseFactory::class)->in(Scope::SINGLETON);

        $this->bind(ExceptionHandlerInterface::class)
            ->to(ExceptionHandler::class)->in(Scope::SINGLETON);

        $this->bind(ResponseEmitterInterface::class)
            ->to(ResponseEmitter::class)->in(Scope::SINGLETON);

        $this->bind(ApplicationInterface::class)
            ->to(Application::class)->in(Scope::SINGLETON);
    }
}
