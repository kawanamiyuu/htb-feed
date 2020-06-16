<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;
use Relay\RelayBuilder as RequestHandlerFactory;
use Throwable;

class Application implements ApplicationInterface
{
    private RequestHandlerFactory $factory;

    private ServerRequestInterface $request;

    private ExceptionHandlerInterface $exceptionHandler;

    private ResponseEmitterInterface $responseEmitter;

    public function __construct(
        RequestHandlerFactory $factory,
        ServerRequestInterface $request,
        ExceptionHandlerInterface $exceptionHandler,
        ResponseEmitterInterface $responseEmitter
    ) {
        $this->factory = $factory;
        $this->request = $request;
        $this->exceptionHandler = $exceptionHandler;
        $this->responseEmitter = $responseEmitter;
    }

    /**
     * @param string[] $handlers
     */
    public function __invoke(array $handlers): void
    {
        $requestHandler = $this->factory->newInstance($handlers);

        try {
            $response = $requestHandler->handle($this->request);
        } catch (Throwable $th) {
            $response = ($this->exceptionHandler)($th);
        }

        ($this->responseEmitter)($response);
    }
}
