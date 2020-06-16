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
    private ServerRequestInterface $request;

    private RequestHandlerFactoryInterface $requestHandlerFactory;

    private ExceptionHandlerInterface $exceptionHandler;

    private ResponseEmitterInterface $responseEmitter;

    public function __construct(
        ServerRequestInterface $request,
        RequestHandlerFactoryInterface $requestHandlerFactory,
        ExceptionHandlerInterface $exceptionHandler,
        ResponseEmitterInterface $responseEmitter
    ) {
        $this->request = $request;
        $this->requestHandlerFactory = $requestHandlerFactory;
        $this->exceptionHandler = $exceptionHandler;
        $this->responseEmitter = $responseEmitter;
    }

    /**
     * @param string[] $handlers
     */
    public function __invoke(array $handlers): void
    {
        $requestHandler = ($this->requestHandlerFactory)($handlers);

        try {
            $response = $requestHandler->handle($this->request);
        } catch (Throwable $th) {
            $response = ($this->exceptionHandler)($th);
        }

        ($this->responseEmitter)($response);
    }
}
