<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Framework;

use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Ray\Di\ProviderInterface;

class ResponseEmitterProvider implements ProviderInterface
{
    public function get(): ResponseEmitterInterface
    {
        return new ResponseEmitter(new SapiStreamEmitter());
    }
}
