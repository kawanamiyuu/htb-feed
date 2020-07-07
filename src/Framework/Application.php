<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * @SuppressWarnings("PMD.LongVariable")
 */
class Application implements ApplicationInterface
{
    private RequestHandlerInterface $requestHandler;

    private ExceptionHandlerInterface $exceptionHandler;

    private ResponseEmitterInterface $responseEmitter;

    /**
     * @param RequestHandlerInterface   $requestHandler
     * @param ExceptionHandlerInterface $exceptionHandler
     * @param ResponseEmitterInterface  $responseEmitter
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        ExceptionHandlerInterface $exceptionHandler,
        ResponseEmitterInterface $responseEmitter
    ) {
        $this->requestHandler = $requestHandler;
        $this->exceptionHandler = $exceptionHandler;
        $this->responseEmitter = $responseEmitter;
    }

    public function __invoke(ServerRequestInterface $request): void
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (Throwable $th) {
            $response = ($this->exceptionHandler)($th, $request);
        }

        ($this->responseEmitter)($response);
    }
}
