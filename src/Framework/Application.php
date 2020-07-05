<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * @SuppressWarnings("PMD.LongVariable")
 */
class Application implements ApplicationInterface
{
    private RequestHandlerFactoryInterface $requestHandlerFactory;

    private ExceptionHandlerInterface $exceptionHandler;

    private ResponseEmitterInterface $responseEmitter;

    public function __construct(
        RequestHandlerFactoryInterface $requestHandlerFactory,
        ExceptionHandlerInterface $exceptionHandler,
        ResponseEmitterInterface $responseEmitter
    ) {
        $this->requestHandlerFactory = $requestHandlerFactory;
        $this->exceptionHandler = $exceptionHandler;
        $this->responseEmitter = $responseEmitter;
    }

    public function __invoke(ServerRequestInterface $request, array $handlers): void
    {
        $requestHandler = ($this->requestHandlerFactory)($handlers);

        try {
            $response = $requestHandler->handle($request);
        } catch (Throwable $th) {
            $response = ($this->exceptionHandler)($th, $request);
        }

        ($this->responseEmitter)($response);
    }
}
