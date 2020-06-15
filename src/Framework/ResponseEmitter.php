<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseEmitter implements ResponseEmitterInterface
{
    private EmitterInterface $emitter;

    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    public function __invoke(ResponseInterface $response): void
    {
        $this->emitter->emit($response);
    }
}
