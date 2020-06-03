<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;

class ResponsePrototypeFactory
{
    /**
     * @return ResponseInterface
     */
    public function newInstance(): ResponseInterface
    {
        return new Response();
    }
}
