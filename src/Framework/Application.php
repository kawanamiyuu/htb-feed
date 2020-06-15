<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;
use Relay\Relay;
use Throwable;

class Application implements ApplicationInterface
{
    private ServerRequestInterface $request;

    private MiddlewareResolverInterface $middlewareResolver;

    private ExceptionHandlerInterface $exceptionHandler;

    private ResponseEmitterInterface $responseEmitter;

    public function __construct(
        ServerRequestInterface $request,
        MiddlewareResolverInterface $middlewareResolver,
        ExceptionHandlerInterface $exceptionHandler,
        ResponseEmitterInterface $responseEmitter
    ) {
        $this->request = $request;
        $this->middlewareResolver = $middlewareResolver;
        $this->exceptionHandler = $exceptionHandler;
        $this->responseEmitter = $responseEmitter;
    }

    /**
     * @param string[] $handlers
     */
    public function __invoke(array $handlers): void
    {
        $requestHandler = new Relay($handlers, function ($handler) {
            return ($this->middlewareResolver)($handler);
        });

        try {
            $response = $requestHandler->handle($this->request);
        } catch (Throwable $th) {
            $response = ($this->exceptionHandler)($th);
        }

        ($this->responseEmitter)($response);
    }
}
